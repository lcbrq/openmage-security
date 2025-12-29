<?php

class LCB_Security_Block_Adminhtml_Rejected_Request_Renderer_MatchedInput extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $matchedStopWord = (string)$row->getMatchedWord();
        $storedPostBodyJson = (string)$row->getPostBody();

        if ($matchedStopWord === '' || $storedPostBodyJson === '') {
            return '';
        }

        $payload = $this->decodeJsonSafely($storedPostBodyJson);

        $postFields = array();
        $rawRequestBody = '';

        if (is_array($payload)) {
            if (!empty($payload['post']) && is_array($payload['post'])) {
                $postFields = $payload['post'];
            }
            if (!empty($payload['raw']) && is_string($payload['raw'])) {
                $rawRequestBody = $payload['raw'];
            }
        }

        $previewLines = array();

        foreach ($postFields as $fieldName => $fieldValue) {
            if (is_array($fieldValue) || is_object($fieldValue)) {
                continue;
            }

            $fieldName = (string)$fieldName;

            if ($this->isTechnicalField($fieldName)) {
                continue;
            }

            $fieldValueText = trim(preg_replace('/\s+/', ' ', (string)$fieldValue));
            if ($fieldValueText === '') {
                continue;
            }

            if (stripos($fieldValueText, $matchedStopWord) !== false) {
                $previewLines[] = $fieldName . ': ' . $fieldValueText;
            }
        }

        $tooltipLines = $previewLines;

        if ($rawRequestBody !== '') {
            $tooltipLines[] = 'raw: ' . $this->truncateText($rawRequestBody, 1500);
        }

        if (!$previewLines && $rawRequestBody !== '') {
            $previewLines[] = 'raw: ' . $this->truncateText($rawRequestBody, 160);
        }

        if (!$previewLines) {
            return '';
        }

        $previewText = $this->truncateText(implode("\n", $previewLines), 160);
        $tooltipText = $this->truncateText(implode("\n", $tooltipLines), 4000);

        return sprintf(
            '<span title="%s">%s</span>',
            htmlspecialchars($tooltipText, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($previewText, ENT_QUOTES, 'UTF-8')
        );
    }

    protected function decodeJsonSafely($jsonString)
    {
        try {
            return Zend_Json::decode((string)$jsonString);
        } catch (Exception $e) {
            return null;
        }
    }

    protected function isTechnicalField($fieldName)
    {
        $fieldName = strtolower((string)$fieldName);

        return in_array($fieldName, array(
            'form_key',
            'success_url',
            'error_url',
            'url',
            'uenc',
            'redirect_url',
            'back_url',
        ), true);
    }

    protected function truncateText($text, $limit)
    {
        $text = (string)$text;

        if (function_exists('mb_strlen') && mb_strlen($text, 'UTF-8') > $limit) {
            return mb_substr($text, 0, $limit, 'UTF-8') . '…';
        }

        if (strlen($text) > $limit) {
            return substr($text, 0, $limit) . '…';
        }

        return $text;
    }
}
