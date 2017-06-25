<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   swisschess
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe
 */

/**
 * Class SwissChess
 *
 * @copyright  Frank Hoppe
 * @author     Frank Hoppe
 * @package    Devtools
 */
class SwissChess extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_swisschess';

	/**
	 * Return if the file does not exist
	 * @return string
	 */
	public function generate()
	{
		// Wenn Datei-UUID fehlt, Ausgabe mit Meldung beenden
		if($this->swt_file == '') return 'UUID fehlt';

		// UUID auflösen, bei Fehler Ausgabe mit Meldung beenden
		$objFile = \FilesModel::findByPk($this->swt_file);
		if ($objFile === null) return 'UUID nicht gefunden';

		// Dateiname zuweisen und Ausgabe weitergenerieren
		$this->swt_file = $objFile->path;
		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{

		$this->Template = new \FrontendTemplate($this->strTemplate);

		require_once 'swt/swtparser.php'; // SWT-Parser einbinden

		$tournament = swtparser($this->swt_file);

		$language = 'de';
		$field_names = swtparser_get_field_names($language);

		if (count($field_names) == 0) {
			$content .= 'Language <em>'.$language.'</em> currently not supported.<br><br>';
		}

		$content .= $this->swtparser_out_tabular($tournament['out'], $field_names);

		if ($tournament['out'][35]) {
			$content .= '<h2 id="teams">Teams</h2>';
			$content .= $this->swtparser_out_info($tournament['out']['Teams'], $field_names);
			$content .= $this->swtparser_out_fixtures($tournament['out']['Mannschaftspaarungen'], 'Mannschaftspaarungen', 'mm-paarungen', $field_names);
		}
		$content .= '<h2 id="spieler">Players / Spieler</h2>';
		$content .= $this->swtparser_out_info($tournament['out']['Spieler'], $field_names);
		$content .= $this->swtparser_out_fixtures($tournament['out']['Einzelpaarungen'], 'Einzelpaarungen', 'ez-paarungen', $field_names);


		$this->Template->content = $content;
		//$this->Template->debug = $tournament;
	}

	/**
	* Shows a list of keys and their values
	* Zeigt eine Liste von Schlüsseln und ihren Werten
	*
	* @param array $tournament (returned array from swtparser())
	* @param array $field_names (returned array from swtparser_get_field_names())
	* @return string HTML output
	* @see swtparser()
	*/
	function swtparser_out_tabular($tournament, $field_names) {
		$output = '<table class="data">';
		$i = 0;
		foreach (array_keys($tournament) as $key) {
			$i++;
			$output .= '<tr class="'.($i & 1 ? 'un' : '').'even"><th>'.(isset($field_names[$key]) ? $field_names[$key] : $key).'</th><td>';
			if (!is_array($tournament[$key])) {
				$output .= (preg_match('/^\d+\-\d+$/', $tournament[$key]) && $field_names[$tournament[$key]] ? $field_names[$tournament[$key]] : $tournament[$key]);
			} else {
				$output .= '(see below / siehe unten)';
			}
			$output .= '</td></tr>'."\n";
		}
		$output .= '</table>';
		return $output;
	}

	/**
	* Shows general information about players and teams
	* Zeigt allgemeine Informationen über Spieler und Teams
	*
	* @param array $data (part of returned array from swtparser())
	* @param array $field_names (returned array from swtparser_get_field_names())
	* @return string HTML output
	* @see swtparser()
	*/
	function swtparser_out_info($data, $field_names) {
		if (!$data) return '<p>No data available. / Keine Daten vorhanden.</p>';
		$output = '<table class="data"><thead><th>ID</th>';
		$head = reset($data);
		foreach (array_keys($head) as $th) {
			$output .= '<th><span>'.($field_names[$th] ? $field_names[$th] : $th).'</span></th>';
		}
		$output .= '</thead><tbody>';
		$i = 0;
		foreach (array_keys($data) as $id) {
			$i++;
			$output .= '<tr class="'.($i & 1 ? 'un' : '').'even"><th>'.$id.'</th>';
			foreach (array_keys($data[$id]) as $key) {
				$output .= '<td>'.$data[$id][$key].'&nbsp;</td>';
			}
			$output .= '</tr>';
		}
		$output .= '</tbody></table>';
		return $output;
	}

	/**
	* Shows all fixtures of a tournament
	* Zeigt alle Paarungen eines Turniers
	*
	* @param array $fixtures (part of returned array from swtparser())
	* @param string $title (optional, HTML heading)
	* @param string $id (optional, HTML heading id attribute)
	* @param array $field_names (returned array from swtparser_get_field_names())
	* @return string HTML output
	*/
	function swtparser_out_fixtures($fixtures, $title = 'Paarungen', $id = 'paarungen', $field_names = array()) {
		if (!$fixtures) return '<p>No data available. / Keine Daten vorhanden.</p>';
		$output = '<h2 id="'.$id.'">'.$title.'</h2>';
		$output .= '<table class="data"><thead><th>Round / Runde</th>';
		$head = reset($fixtures);
		$head = reset($head);
		foreach (array_keys($head) as $th) {
			$output .= '<th>'.(isset($field_names[$th]) ? $field_names[$th] : $th).'</th>';
		}
		$output .= '</thead>';
		foreach ($fixtures AS $player => $rounds) {
			$i = 0;
			$output .= '<tr><td>'.$player.'</td><td></td></tr>';
			// print first line with keys as head
			foreach ($rounds as $round => $data) {
				$i++;
				$output .= '<tr class="'.($i & 1 ? 'un' : '').'even">';
				$output .= '<th>'.$round.'</th>';
				foreach (array_keys($data) as $key)
					$output .= '<td>'.(preg_match('/^\d+\-\d+$/', $data[$key]) && isset($field_names[$data[$key]]) ? $field_names[$data[$key]] : $data[$key]).'</td>';
				$output .= '</tr>';
			}
			$output .= '<tr><td>&nbsp;</td><td></td></tr>';
		}
		$output .= '</table>';
		return $output;
	}

}
