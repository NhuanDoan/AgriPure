    <?php
    class Block {
        public $index;
        public $timestamp;
        public $stage;
        public $data;
        public $previousHash;
        public $hash;
        public $nonce;

        public function __construct($index, $timestamp, $stage, $data, $previousHash) {
            $this->index = $index;
            $this->timestamp = $timestamp;
            $this->stage = $stage;
            $this->data = $data; // JSON string
            $this->previousHash = $previousHash;
            $this->nonce = 0;
            $this->hash = $this->calculateHash();
        }

        public function calculateHash() {
            return hash('sha256', $this->index . $this->timestamp . $this->stage . $this->data . $this->previousHash . $this->nonce);
        }

        public function mineBlock($difficulty = 4) {
            while (substr($this->hash, 0, $difficulty) !== str_repeat("0", $difficulty)) {
                $this->nonce++;
                $this->hash = $this->calculateHash();
            }
        }
    }
?>
