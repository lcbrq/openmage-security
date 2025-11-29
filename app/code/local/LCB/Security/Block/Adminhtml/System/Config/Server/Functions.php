<?php

class LCB_Security_Block_Adminhtml_System_Config_Server_Functions extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $functions = [
            'exec',
            'shell_exec',
            'passthru',
            'proc_open',
            'popen',
            'system'
        ];

        $html .= '';
        foreach ($functions as $fn) {
            $enabled = function_exists($fn);
            $html .= sprintf(
                '<tr>
                    <td style="padding:4px 8px;">%s</td>
                    <td style="padding:4px 8px; font-weight:bold; color:%s;">%s</td>
                </tr>',
                $fn,
                $enabled ? 'red' : 'green',
                $enabled ? $this->__('Enabled') : $this->__('Disabled')
            );
        }

        return $html;
    }
}
