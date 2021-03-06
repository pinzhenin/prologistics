<?php
namespace label\AliasCorrector;

use label\DB;

/**
 * Class Corrector
 * It works with aliases of content, services, offers etc.
 * It has rules to process aliases to proper url and mechanism to catch bad untracked aliases.
 */
class Corrector
{
    const AREA_CONTENT = 'content';
    const AREA_SERVICE = 'service';
    const AREA_CATEGORY = 'category';
    const AREA_OFFER = 'offer';
    const AREA_NEWS = 'news';

    private static $areas = [
        self::AREA_CONTENT => [
            'table' => 'shop_content',
            'field' => 'alias',
        ],
        self::AREA_SERVICE => [
            'table' => 'shop_service',
            'field' => 'alias',
        ],
        self::AREA_CATEGORY => [
            'table' => 'shop_catalogue',
            'field' => 'alias',
        ],
        self::AREA_OFFER => [
            'table' => 'sa',
            'field' => 'ShopSAAlias',
        ],
        self::AREA_NEWS => [
            'table' => 'shop_news',
            'field' => 'alias',
        ],
    ];

    /**
     * All aliases actually stored in db
     * @var string[]
     */
    private $aliasesRaw;

    /**
     * List of aliases should be stored
     * It contain only incorrect aliases that could be changed
     * @var string[]
     */
    private $aliasesManageable;

    /**
     * List of aliases could not be processed with current rules
     * @var string[]
     */
    private $aliasesProblem;

    /**
     * Linker to connect alias id to essence id (offer, news etc.)
     * @var int[]
     */
    private $aliasesIDs;

    /**
     * List to store language of alias
     * @var string[]
     */
    private $aliasesLanguages;

    /**
     * Corrector constructor.
     * @param string $area what area aliases shall we correct
     */
    public function __construct($area)
    {
        if (!key_exists($area, self::$areas)) {
            throw new \Exception('Bad param $area');
        }

        $dbr = DB::getInstance(DB::USAGE_WRITE);

        $aliases = $dbr->getAll(
            '
                SELECT iid, value, id, language 
                FROM translation 
                WHERE 
                    table_name = ?
                    AND field_name = ?',
            null,
            [
                self::$areas[$area]['table'],
                self::$areas[$area]['field'],
            ]
        );
        foreach ($aliases as $alias) {
            $this->aliasesRaw[$alias->iid] = $alias->value;
            $this->aliasesIDs[$alias->iid] = $alias->id;
            $this->aliasesLanguages[$alias->iid] = $alias->language;
        }
    }

    /**
     * Store processed aliases instead of wrong aliases
     * @throws \Exception
     * @todo implement
     * @return bool if it was correctly executed
     */
    public function correct()
    {
        throw new \Exception('Correction did not implemented yet', 502);

        if (is_null($this->aliasesManageable)) {
            $this->findManageableAliases();
        }
        if (count($this->aliasesManageable) > 0) {
            $db = DB::getInstance(DB::USAGE_READ);
            foreach ($this->aliasesManageable as $iid => $alias) {
            }
            //$db->query();//@todo check return
        }
        //@todo return something
        return true;
    }

    /**
     * Getter for all currently stored aliases
     * @return \string[]
     */
    public function getRawAliases()
    {
        return $this->aliasesRaw;
    }

    /**
     * Getter for aliases that should be stored instead of wrong aliases.
     * @return \string[]
     */
    public function getManageableAliases()
    {
        if (is_null($this->aliasesManageable)) {
            $this->findManageableAliases();
        }
        return $this->aliasesManageable;
    }

    /**
     * Getter for problem aliases
     * Returns list of aliases that should but could not be processed with current rules
     * @return \string[]
     */
    public function getProblemAlieases()
    {
        if (is_null($this->aliasesProblem)) {
            $this->findProblemAliases();
        }
        return $this->aliasesProblem;
    }

    /**
     * Return essence id of alias (essence could be offer, news etc.)
     * @param int $iid alias id
     * @return int
     */
    public function getEssenceId($iid)
    {
        return $this->aliasesIDs[$iid];
    }

    /**
     * Return alias language
     * @param int $iid alias id
     * @return string
     */
    public function getLanguage($iid)
    {
        return $this->aliasesLanguages[$iid];
    }

    /**
     * Generating list of problem aliases
     */
    private function findProblemAliases()
    {
        $this->aliasesProblem = [];
        foreach ($this->aliasesRaw as $iid => $alias) {
            if (isset($this->getManageableAliases()[$iid])) {
                $alias = $this->getManageableAliases()[$iid];
            }
            if (urlencode($alias) !== $alias) {
                $this->aliasesProblem[$iid] = urlencode($alias);
            }
        }
    }

    /**
     * Generating list of manageable aliases
     */
    private function findManageableAliases()
    {
        $this->aliasesManageable = [];
        foreach ($this->aliasesRaw as $iid => $alias) {
            $processedAlias = $this->replaceChars($alias);
            if ($alias !== $processedAlias) {
                $this->aliasesManageable[$iid] = $processedAlias;
            }
        }
    }

    /**
     * Replace wrong url characters with proper chars
     * @param string $alias
     * @return string
     */
    private function replaceChars($alias)
    {
        $rules = [
            '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'Ae', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'a',
            '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'ae', '??' => 'a', '??' => 'a', '??' => 'ae',
            '??' => 'b', '??' => 'B',
            '??' => 'C', '??' => 'c', '??' => 'C', '??' => 'c', '??' => 'c', '??' => 'C',
            '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'e',
            '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'E',
            '??' => 'G', '??' => 'g',
            '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i',
            '??' => 'L', '??' => 'l',
            '??' => 'N', '??' => 'N', '??' => 'n',
            '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'Oe', '??' => 'O', '??' => 'oe', '??' => 'o', '??' => 'O', '???' => 'O',
            '??' => 'o', '??' => 'n', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '???' => 'o',
            '??' => 'r', '??' => 'R',
            '??' => 'S', '??' => 's', '??' => 'S', '??' => 's', '??' => 'S', '??' => 's', '??' => 'ss', '??' => 'S', '??' => 's',
            '??' => 't', '??' => 'T',
            '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'Ue', '??' => 'U',
            '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'ue', '??' => 'u',
            '??' => 'Y',
            '??' => 'y', '??' => 'y',
            '??' => 'Z', '??' => 'z', '??' => 'Z', '??' => 'z', '??' => 'Z', '??' => 'z',
            '&ouml;' => 'o', '&uuml;' => 'u',
            ';' => '-', '&' => '-',
            '\'' => '-', ',' => '-', '(' => '-', ')' => '-', '+' => '-', '??' => '-', '???' => '-',
            ' ' => '-', '!' => '', '%' => '', '#' => '-', '/' => '-', '???' => '-', '>' => '-', '?' => '-', '??' => '-',
            '`' => '-', ':' => '-', '=' => '-', '???' => '-', '*' => '-', '???' => '-', '<' => '-', '[' => '-', ']' => '-', "\t" => '-',
            '{' => '-', '}' => '-',
        ];
        return strtr(strtr(strtr($alias, $rules), ['---' => '-']), ['--' => '-']);
    }
}