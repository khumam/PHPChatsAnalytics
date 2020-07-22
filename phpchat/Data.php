<?php

require  'Database.php';

class Data
{
    protected $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getDatabase();
    }

    private function _decodeEmoticons($src)
    {
        $replaced = preg_replace("/\\\\u([0-9A-F]{1,4})/i", "&#x$1;", $src);
        $result = mb_convert_encoding($replaced, "UTF-16", "HTML-ENTITIES");
        $result = mb_convert_encoding($result, 'utf-8', 'utf-16');
        return $result;
    }

    public function getMostEmojiUsed()
    {
        $emojis = [];
        $labels = [];
        $values = [];
        $colors = [];
        $result = [];
        $mostActive = $this->pdo->prepare("SELECT emoji FROM datachat ORDER BY emoji ASC");
        $mostActive->execute();
        $data = $mostActive->fetchAll();

        for ($i = 0; $i < count($data); $i++) {
            $emoji = json_decode($data[$i]['emoji']);
            for ($j = 0; $j < count($emoji[0]); $j++) {
                $string = substr_replace($emoji[0][$j], "\\", 0, 0);
                $emojis[] = substr_replace($string, "\\", 6, 0);
            }
        }

        $emojiData = array_count_values($emojis);

        foreach ($emojiData as $label => $value) {
            $labels[] = $label;
            $values[] = $value;
            $colors[] = $this->_rand_color();
        }

        $result['labels'] = implode('", "', $labels);
        $result['data'] = implode(', ', $values);
        $result['colors'] = implode('", "', $colors);
        $result['type'] = 'pie';

        return $result;
    }

    public function getMost($parameter)
    {
        $labels = [];
        $values = [];
        $result = [];
        $color = [];
        $mostActive = $this->pdo->prepare("SELECT $parameter, count(*) as total FROM datachat GROUP BY $parameter ORDER BY total DESC");
        $mostActive->execute();
        $data = $mostActive->fetchAll();

        foreach ($data as $label) {
            $labels[] = $label[$parameter];
            $values[] = $label['total'];
            $color[] = $this->_rand_color();
        }

        $result['labels'] = implode('", "', $labels);
        $result['colors'] = implode('", "', $color);
        $result['data'] = implode(', ', $values);

        if ($parameter == 'date' || $parameter == 'time') {
            $result['type'] = 'bar';
        } else {
            $result['type'] = 'pie';
        }

        return $result;
    }

    private function _rand_color()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    public function getAllMessage()
    {
        $mostActive = $this->pdo->prepare("SELECT CONCAT(message, ' ') FROM `datachat` WHERE `message` != '<Media omitted>'");
        $mostActive->execute();
        $data = $mostActive->fetchAll();

        return $data;
    }
}
