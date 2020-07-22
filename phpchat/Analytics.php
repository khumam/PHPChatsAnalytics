<?php

require 'Database.php';

class Analytics
{
    protected $filename;
    protected $data = [];
    protected $database;
    protected $totalData = 0;

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->_extractData();
    }

    private function _extractData()
    {
        $filename = $this->filename;
        $data = fopen($filename, 'r');
        while ($perLine = fgets($data)) {
            if ('' === trim($perLine)) {
                continue;
            } else {
                $text = trim($perLine);
                $timestamp = substr($text, 0, strpos($text, 'M - '));
                if ('' === trim($timestamp)) {
                    continue;
                } else {
                    if ('' !== trim(substr($text, 0, strpos($text, ' left'))) || '' !== trim(substr($text, 0, strpos($text, ' changed to '))) || '' !== trim(substr($text, 0, strpos($text, ' added '))) || '' !== trim(substr($text, 0, strpos($text, ' You were added'))) || '' !== trim(substr($text, 0, strpos($text, ' created group')))) {
                        continue;
                    } else {
                        $datetime = explode(', ', $timestamp);
                        $this->data['date'][] = $datetime[0];
                        $this->data['time'][] = $datetime[1] . 'M';
                        $this->data['message'][] = str_replace('"', "", trim(substr($text, strpos($text, ': ') + 1)));
                        $this->data['contact'][] = str_replace("'", "", trim(substr(trim(substr($text, 0, strpos($text, ': '))), strpos(trim(substr($text, 0, strpos($text, ': '))), ' - ') + 2)));
                        $this->data['emoji'][] = $this->_getEmoji(trim(substr($text, strpos($text, ': ') + 1)));
                        $this->totalData += 1;
                    }
                }
            }
        }
        fclose($data);
        $connection = new Database();
        $this->database = $connection->getDatabase();
    }

    public function getData($type, $max = null)
    {
        $data = $this->data;
        if ($max == null) {
            for ($index = 0; $index < count($data); $index++) {
                echo $data[$type][$index] . "\n";
            }
        } else {
            for ($index = 0; $index < $max; $index++) {
                echo $data[$type][$index] . "\n";
            }
        }
    }

    public function insertData()
    {
        $lists = $this->data;

        $filename = $this->database->prepare("SELECT id FROM filename WHERE filename = '$this->filename'");
        $filename->execute();
        $checkFileName = $filename->fetchAll();

        if (!empty($checkFileName)) {
            $filename_id = $checkFileName['id'];
        } else {
            $insertFileData = $this->database->prepare("INSERT INTO filename (`filename`) VALUES ('$this->filename')");
            $insertFileData->execute();
            $filename_id = $this->database->lastInsertId();
        }

        for ($index = 0; $index < $this->totalData; $index++) {
            $date = $lists['date'][$index];
            $datetime = date('Y-m-d', strtotime("$date"));
            $time = $lists['time'][$index];
            $message = str_replace("'", '', $lists['message'][$index]);
            $contact = $lists['contact'][$index];
            $emoji = $lists['emoji'][$index];
            $links = $this->_countLinks($message);
            $letter_count = strlen($message);
            $word_count = str_word_count($message);
            $insertData = $this->database->exec("INSERT INTO datachat (filename_id, date, time, contact, message, emoji, url, letter_count, word_count) VALUES ($filename_id, '$datetime', '$time', '$contact', '$message', '$emoji', $links, $letter_count, $word_count)");
        }
    }

    private function _countLinks($string)
    {
        $pattern = '~[a-z]+://\S+~';
        if ($total = preg_match_all($pattern, $string, $out)) {
            return $total;
        } else {
            return 0;
        }
    }

    private function _getEmoji($string)
    {
        preg_match_all('/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', $string, $emojis);

        return json_encode($emojis);
    }

    public function getStats()
    {
        $result = "Total chat: " . count($this->data['message']) . "\n";
        $result .= "Total member: " . count(array_count_values($this->data['contact'])) . "\n";
        $result .= "Total Media: " . array_count_values($this->data['message'])["<Media omitted>"] . "\n";
        echo $result;
    }
}
