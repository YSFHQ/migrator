<?php namespace YSFHQ\Infrastructure\Clients;

class BitfieldHelper
{
    var $bitfield_data;

    function bitfield($bitfield = '')
    {
        $this->bitfield_data = base64_decode($bitfield);
    }
    /**
    */
    function get($n)
    {
        // Get the ($n / 8)th char
        $byte = $n >> 3;
        if (strlen($this->bitfield_data) >= $byte + 1)
        {
            $c = $this->bitfield_data[$byte];
            // Lookup the ($n % 8)th bit of the byte
            $bit = 7 - ($n & 7);
            return (bool) (ord($c) & (1 << $bit));
        }
        else
        {
            return false;
        }
    }
    function set($n)
    {
        $byte = $n >> 3;
        $bit = 7 - ($n & 7);
        if (strlen($this->bitfield_data) >= $byte + 1)
        {
            $this->bitfield_data[$byte] = $this->bitfield_data[$byte] | chr(1 << $bit);
        }
        else
        {
            $this->bitfield_data .= str_repeat("\0", $byte - strlen($this->bitfield_data));
            $this->bitfield_data .= chr(1 << $bit);
        }
    }
    function clear($n)
    {
        $byte = $n >> 3;
        if (strlen($this->bitfield_data) >= $byte + 1)
        {
            $bit = 7 - ($n & 7);
            $this->bitfield_data[$byte] = $this->bitfield_data[$byte] &~ chr(1 << $bit);
        }
    }
    function get_blob()
    {
        return $this->bitfield_data;
    }
    function get_base64()
    {
        return base64_encode($this->bitfield_data);
    }
    function get_bin()
    {
        $bin = '';
        $len = strlen($this->bitfield_data);
        for ($i = 0; $i < $len; ++$i)
        {
            $bin .= str_pad(decbin(ord($this->bitfield_data[$i])), 8, '0', STR_PAD_LEFT);
        }
        return $bin;
    }
    function get_all_set()
    {
        return array_keys(array_filter(str_split($this->get_bin())));
    }
    function merge($bitfield)
    {
        $this->bitfield_data = $this->bitfield_data | $bitfield->get_blob();
    }
}
