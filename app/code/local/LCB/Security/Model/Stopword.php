<?php

class LCB_Security_Model_Stopword extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('lcb_security/stopword');
    }

    /**
     * @param string $text
     * @return string|null
     */
    public function findMatchedWordInText($text)
    {
        $textLower = mb_strtolower($text, 'UTF-8');

        $words = $this->getCollection()
            ->addFieldToFilter('is_active', 1)
            ->getColumnValues('word');

        foreach ($words as $word) {
            $word = trim((string)$word);
            if ($word && mb_stripos($textLower, $word, 0, 'UTF-8') !== false) {
                return $word;
            }
        }

        return null;
    }
}
