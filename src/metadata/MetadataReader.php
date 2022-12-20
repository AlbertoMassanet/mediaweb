<?php



class MetadataReader
{
    static protected function valuesFromDOMNodes($nodeArr)
    {
        
        if ($nodeArr->length == 1) return $nodeArr->item(0)->nodeValue;
        elseif ($nodeArr->length > 1) {
            foreach ($nodeArr as $value)
                $t[] = $value->nodeValue;
            return $t;
        } else return '';
    }

    static protected function getMultipleNodesDOMList($nodes, $enums)
    {
        $a = $b = [];
        foreach ($nodes as $node) {
            foreach ($enums as $enum)
            {
                $n = $node->getElementsByTagName($enum);
                if ($n->length > 0) $a[$enum] = self::valuesFromDOMNodes($n);
            }
            $b[] = $a;
            //echo "enum: $enum " . print_r($a, true) . "<br>";
        }
        return $b;
    }

    static public function getMetadata($file, $tagnames, $tagCover = "", $pathCover = "")
    {
        $xml = new \DOMDocument();
        
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;

        if (!@$xml->load($file)) return [];
        

        $t_arr = [];
        foreach ($tagnames as $tagname)
        {
            if (is_array(($tagname))) 
            {
                foreach ($tagname as $k => $v)
                {
                    $node = $xml->getElementsByTagName((string)$k);
                    $t_arr[$k] = self::getMultipleNodesDOMList($node, $v);
                }
                
                
            } else {
                $node = $xml->getElementsByTagName((string)$tagname);       
                if ($node->length > 0) $t_arr[$tagname] = self::valuesFromDOMNodes($node); 
            }
        }
        // foreach ($tagnames as $tag)
        // {
        //     $node = $xml->getElementsByTagName((string)$tag);       
        //     $t_arr[$tag] = self::valuesFromDOMNodes($node);
        // }

        if ($tagCover != "")
        {
            $c_node = $xml->getElementsByTagName($tagCover);
            if ($c_node->length > 0) $c_tag = $c_node->item(0);
            if ($c_tag && $c_tag->hasAttributes())
                foreach ($c_tag->attributes as $att)
                    if ($att->nodeName == 'href') $t_arr['cover'] =  $pathCover. DIRECTORY_SEPARATOR . $att->nodeValue;
        }

        return $t_arr;
    }

    static public function getMetadataVideo($file, Array $tagnames)
    {
        $xml = new \DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;

        if (!@$xml->load($file)) return [];

        $t_arr = $t2_arr = [];
        foreach ($tagnames as $tagkey => $tag)
        {
            if (is_array($tag))
            {
                foreach($tag as $key => $value)
                {
                    $node = $xml->getElementsByTagName((string)$key);
                    if ($node->length > 0) {
                        foreach ($value as $subtags)
                        {
                            $node2 = $node->item(0)->getElementsByTagName((string)$subtags);
                            if ($node2->length > 0) $t2_arr[$subtags] = $node2->item(0)->nodeValue;
                        }   
                    }
                }
                // die('<pre>'.print_r($t2_arr,true).'</pre>');
                $t_arr[$tagkey] = $t2_arr;
            } else {
                $node = $xml->getElementsByTagName((string)$tag);       
                $t_arr[$tag] = self::valuesFromDOMNodes($node);
            }
        }

        return $t_arr;

    }
}