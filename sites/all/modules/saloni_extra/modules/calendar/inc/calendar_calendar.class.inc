<?php

/**
 * Class Calendar
 *
 * Calendar build helper. Mostly useful for dynamically class addition
 */
class Calendar {
	private $calendar = '';
	private $language;

	public function __construct($language) {
		$this->language = $language;
		$this->calendar = "<table id='calendar-block' lang='" . $language . "'>";
	}

	/**
	 * Adds Calendar title
	 *
	 * @param $value
	 * @param string $goto
	 */
	public function addHeader($value, $goto = '') {
		if(!empty($goto)) {

			drupal_add_library('system', 'ui.datepicker');
			$monthYearNav = date('Y, n - 1, d, H, i, s', $value);
			$date_prev = format_date(strtotime($goto .' -1 month'), 'custom', 'Y-m');
			$date_next = format_date(strtotime($goto .' +1 month'), 'custom', 'Y-m');

			$nav_attr_prev = array('data-goto' => $date_prev);
			$nav_attr_next = array('data-goto' => $date_next);


			$this->calendar .= "<thead>
									<tr>
			                          <td colspan=7 class='head-navigate'>
										<div class='nav left-nav'>" . l('<div class="calendar-nav calendar-nav-left"></div>', '', array('html' => true, 'attributes' => $nav_attr_prev)) . "</div>
										<div class='calendar-block-title date-picker'></div>
										<div class='nav right-nav'>" . l('<div class="calendar-nav calendar-nav-right"></div>', '', array('html' => true,'attributes' => $nav_attr_next)) . "</div>
									  </td>
						  			</tr>
								</thead>";

			$this->calendar .= "
				<script>jQuery(document).ready(function($){
					var months = ['January', 'February', 'March', 'April', 'May', 'June',
					'July', 'August', 'September', 'October', 'November', 'December'];
        			var language = jQuery('#calendar-block').attr('lang');
					function showLoader() {
						var loader = jQuery(document.createElement('div')).attr('id', 'calendar-ajax-loader').show();
						jQuery('#calendar-block-wrapper').append(loader);
					}
					function hideLoader() {
						jQuery('#calendar-ajax-loader').remove();
					}

					jQuery('.date-picker').datepicker({
						defaultDate: new Date(".$monthYearNav."),
			    		changeMonth: true,
	        			changeYear: true,
	        			monthNamesShort: months,
	        			onChangeMonthYear: function (year, month, inst) {
	            			var date = year + '-' + month;
	            			showLoader();
          					jQuery.ajax({
								url: Drupal.settings.basePath + 'calendar-navigation',
								type: 'POST',
								dataType: 'json',
								data: 'date=' + date + '&language=' + language,
								success: function(data) {
									hideLoader();
									jQuery('#calendar-block-wrapper').html(data.calendar);
									jQuery('#calendar-read-more').attr('href', jQuery('.calendar-block-title a').attr('href'));
								}
							});
	        			}
			  		});
				});
			</script>";
		}
		else {
			$this->calendar .= '<tbody>';
			$this->calendar .= "<tr>
									<th class='calendar-block-title' colspan=7>". $value ."</th>
					  			</tr>";
		}
		$mon = mb_substr(t('Monday', array(), array('langcode' => $this->language)), 0, 3);
		$tue = mb_substr(t('Tuesday', array(), array('langcode' => $this->language)), 0, 3);
		$wed = mb_substr(t('Wednesday', array(), array('langcode' => $this->language)), 0, 3);
		$thu = mb_substr(t('Thursday', array(), array('langcode' => $this->language)), 0, 3);
		$fri = mb_substr(t('Friday', array(), array('langcode' => $this->language)), 0, 3);
		$sat = mb_substr(t('Saturday', array(), array('langcode' => $this->language)), 0, 3);
		$sun = mb_substr(t('Sunday', array(), array('langcode' => $this->language)), 0, 3);
		$this->calendar .= "<tr>
								<td class='day-title-cell' data-name='" . $mon . "'>" . $mon . "</td>
								<td class='day-title-cell' data-name='" . $tue . "'>" . $tue . "</td>
								<td class='day-title-cell' data-name='" . $wed . "'>" . $wed . "</td>
								<td class='day-title-cell' data-name='" . $thu . "'>" . $thu . "</td>
								<td class='day-title-cell' data-name='" . $fri . "'>" . $fri . "</td>
								<td class='day-title-cell' data-name='" . $sat . "'>" . $sat . "</td>
								<td class='day-title-cell' data-name='" . $sun . "'>" . $sun . "</td>
							</tr>";
	}

	/**
	 *
	 */
	public function openRowTag() {
		$this->calendar .= '<tr>';
	}

	/**
	 *
	 */
	public function closeRowTag() {
		$this->calendar .= '</tr>';
	}

	/**
	 * Adds <td> to table adn sets all of ot's data
	 *
	 * @param $value
	 * @param array $class
	 * @param array $day_hover_data
	 * @param array $language
	 */
	public function addCell($value, $class = array(), $day_hover_data = array(), $language = array()) {
        $classes = '';
		$dat = '';

		if(!empty($class)) {
			$classes = 'class="' . implode(' ', $class) . '"';
		}

		if(!empty($day_hover_data)) {
			$list = '<div class="ms-tooltip">
						<ul>';
			foreach($day_hover_data as $data) {
                if(!empty($data['nid'])){
    			    $list_text = views_trim_text(array('max_length' => 40, 'word_boundary' => true, 'ellipsis' => '...'), $data['title']);
    			    $link = l($list_text, 'node/'.$data['nid']);
    				$list .= '<li class="ms-tooltip-list-element">' . $link . '</li>';
                }
			}
			$list .= '</ul></div>';
			$value .= $list;
		}
		$this->calendar .= '<td ' . $classes . '><div class="table-cell-inside-div">' .$value .'</div></td>';
	}

	/**
	 * @return string
	 */
	public function finish() {
		$this->calendar .= '</tbody></table>';
		return $this->calendar;
	}
}
