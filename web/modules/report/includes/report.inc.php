<?php

/*
 * (c) 2013 Mandriva, http://www.mandriva.com
 *
 * This file is part of Mandriva Management Console (MMC).
 *
 * MMC is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * MMC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with MMC.  If not, see <http://www.gnu.org/licenses/>.
 */
?>

<style>
    .report-indicator label {
        display: inline !important;
    }
    .report-table {
        margin-bottom: 20px;
    }
</style>
<script>
    var toggleSubIndicators = function(link) {
        var indicator = jQuery(link).parent();
        indicator.nextAll('.report-indicators').toggle(0, function() {
            if (jQuery(this).is(':visible')) {
                indicator.find('a').html("<?php echo _T("Less detail", "report") ?>");
            }
            else {
                indicator.find('a').html("<?php echo _T("More detail", "report") ?>");
            }
        });
    }

    var entitiesSelected = function() {
		// select all entities
        var select = jQuery("#entities");
        select.find('option').each(function() {
            var option = jQuery(this);
            option.prop('selected', true);
        });
    }

    var sectionsSelected = function() {
        // check if something in the section is selected
        jQuery('.report-section').each(function() {
            var section = jQuery(this);
            if (section.find('input:checked').length > 0)
                section.find('> input').val(section.data('name'));
            else
                section.find('> input').val("");
        });
    }

    var checkIndicator = function(check) {
        var indicator = jQuery(check).parent();
        // uncheck child indicators
        indicator.nextAll('.report-indicators').find('input').attr("checked", false);
        // uncheck all parents indicators
        indicator.parent().parents('.report-indicators').find('> .report-indicator > input').attr("checked", false);
        sectionsSelected();
    }

    var checkInitialIndicators = function() {
        var indicators = jQuery('.report-indicators').find('input:checked');
        indicators.each(function() {
            var indicator = jQuery(this).parent();
            var level = 0;
            indicator.parents('.report-indicators').each(function() {
                if (level > 0)
                    jQuery(this).find("> .report-indicator > a").html("<?php echo _T("Less detail", "report") ?>");
                else
                    level += 1;
                jQuery(this).show();
            })
            indicator.parent().nextAll('.report-indicators').show();
            indicator.parent().prevAll('.report-indicators').show();
        })
    }

    jQuery(document).ready(function() {
        checkInitialIndicators();
        sectionsSelected();
        entitiesSelected();
    });
</script>

<?

class ReportModule extends HtmlContainer {

    function ReportModule($name, $sections) {
        $this->HtmlContainer();
        $this->name = $name;
        foreach($sections as $section) {
            $this->elements[] = new ReportSection($section);
        }
    }

    function begin() {
        echo '<div class="report-module" id="' . $this->name . '">';
    }

    function end() {
        echo '</div>';
    }

}

class ReportSection extends HtmlContainer {

    function ReportSection($section) {
        $this->HtmlContainer();
        $this->title = $section['title'];
        $this->name = $section['name'];
        foreach($section['tables'] as $table) {
            $this->elements[] = new ReportTable($table);
        }
    }

    function begin() {
        echo '<div class="report-section" data-name="' . $this->name . '">' .
            '<input checked=checked type="checkbox" id="cbx_' . $this->name . '" name="sections[]" value="" />' .
             '<label style="display: inline; font-size: 15px; font-weight: bold; margin-top: 15px" for="cbx_' . $this->name  . '"> ' . $this->title . '</label>' .
                '<div style="margin-left: 15px"><br />';
    }

    function end() {
        echo '</div>
            </div>';
    }

}

class ReportTable extends HtmlContainer {

    function ReportTable($table) {
        $this->HtmlContainer();
        $this->type = $table['type'];
        $this->title = $table['title'];
        $this->name = $table['name'];
        foreach($table['items'] as $indicator) {
            $this->elements[] = new ReportIndicator($indicator, 0);
        }
    }

    function begin() {
        echo '<div class="report-table">' .
            '<input checked=checked type="checkbox" id="cbx_' . $this->name . '" name="tables[]" value="' . $this->name  . '" />' .
             '<label style="display: inline; font-size: 15px; font-weight: bold; margin-top: 15px" for="cbx_' . $this->name  . '"> ' . $this->title . '</label>';
    }

    function end() {
        echo '</div>';
    }

}

class ReportIndicator extends HtmlContainer {

    function ReportIndicator($indicator, $level) {
        $this->HtmlContainer();
        $this->name = $indicator['indicator'];
        $this->title = $indicator['title'];
        $this->level = $level;
        $this->selected = isset($indicator['selected']) ? $indicator['selected'] : "no";
        $this->hidden = isset($indicator['hidden']) ? $indicator['hidden'] : "no";
        foreach($indicator['items'] as $indicator) {
            $this->elements[] = new ReportIndicator($indicator, ($this->level + 1));
        }
    }

    function display() {
        // Dont show hidden indicators
        // Only used to calculate "other" value
        if ($this->hidden == "no") {
            echo '<div class="report-indicators report-indicator-level-' . $this->level .'" style="margin-left: 15px;';
            if ($this->level > 1)
                echo 'display: none;';
            echo '">
                    <div class="report-indicator">
                      <input type="checkbox" id="' . $this->name . '" name="indicators[' . $this->name . ']" onchange="checkIndicator(this)"';
            if ($this->selected == "yes")
                echo ' checked="checked"';
            echo '    />
                       <label for="' . $this->name . '" class="report-indicator-label">' . $this->title . '</label>';
                        if ($this->elements)
                            echo '&nbsp;&nbsp;(<a href="#" onclick="toggleSubIndicators(this); return false;">' . _T("More detail", "report") . '</a>)';
            echo '</div>';
        }
        if ($this->elements) {
            foreach ($this->elements as $element)
                $element->display();
        }
        if ($this->hidden == "no") {
            echo '</div>';
        }
    }

}

?>
