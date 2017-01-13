<?php

class Dotdigitalgroup_Email_Block_Adminhtml_System_Advanced_Daterange
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $date = new Varien_Data_Form_Element_Date;
        $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $ranges = array('from', 'to');
        $dateElements = '';

        foreach ($ranges as $range) {
            $data = array(
                'name'      => 'refresh_data_' . $range,
                'html_id'   => 'refresh_data_' . $range,
                'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            );
            $range = ucfirst($range);
            $date->setData($data);
            $date->setValue($element->getValue(), $format);
            $date->setFormat(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
            $date->setForm($element->getForm());
            $dateElements .=
                "<div style='width: 200px; margin-bottom: 2px;'>" .
                    "<p style='width:45px !important; margin: 0 !important; display: inline-block; font-weight:bold;'>
                        $range :
                    </p>" . $date->getElementHtml() .
                "</div>";
        }
        $js = "
            <script type='application/javascript'>
                var j = jQuery.noConflict();
                j(document).ready(function() {
                    
                    function updateUrlParameter(uri, key, value) {
                        // remove the hash part before operating on the uri
                        var i = uri.indexOf('#');
                        var hash = i === -1 ? ''  : uri.substr(i);
                             uri = i === -1 ? uri : uri.substr(0, i);
                    
                        var re = new RegExp('([?&])' + key + '=.*?(&|$)', 'i');
                        var separator = uri.indexOf('?') !== -1 ? '&' : '?';
                        if (uri.match(re)) {
                            uri = uri.replace(re, '$1' + key + '=' + value + '$2');
                        } else {
                            uri = uri + separator + key + '=' + value;
                        }
                        return uri + hash; 
                    }
                    
                    var elmToObserve = ['refresh_data_from', 'refresh_data_to'];
                    var elmToChange = 
                        [
                            '#connector_developer_settings_sync_settings_reimport_customers',
                            '#connector_developer_settings_sync_settings_reimport_subscribers',
                            '#connector_developer_settings_sync_settings_reimport_orders', 
                            '#connector_developer_settings_sync_settings_reimport_quotes', 
                            '#connector_developer_settings_sync_settings_reimport_reviews', 
                            '#connector_developer_settings_sync_settings_reimport_wishlist', 
                            '#connector_developer_settings_sync_settings_reimport_catalog'
                        ];
                    j.each(elmToObserve, function( key, value ) {
                      j('#' + value).change(function() {
                          j.each(elmToChange, function( k, v ) {
                              var str = j(v).attr('onclick');
                              var updatedUrl = updateUrlParameter(str, value, encodeURIComponent(j('#' + value).val()));
                              j(v).attr('onclick', updatedUrl);
                          });
                        });
                    });
                }); 
            </script>
        ";
        return $dateElements . $js;
    }
}
