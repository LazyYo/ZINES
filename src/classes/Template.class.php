<?php
class Template{
    protected $file;
    protected $values = [];

    protected $pages = [];


    public function __construct($file, $values = []){

        $this->file   = $file;

        if (!file_exists($this->file)) {
            throw new Exception("Error loading template file ($this->file).");
        }

        $this->values = $values;
    }

    public function set($key, $value) {
        $this->values[$key] = $value;
    }

    public function output() {
        // $output = file_get_contents($this->file);
        extract($this->values);
        ob_start();
            include $this->file;
        $output = ob_get_clean();

        // foreach ($this->values as $key => $value) {
        //     $tagToReplace = "{{".$key."}}";
        //     $output = str_replace($tagToReplace, $value, $output);
        // }


        return $output;
    }

    static public function merge($templates, $separator = "\n") {
        $output = "";

        foreach ($templates as $template) {
            $content = (get_class($template) !== "Template")
                ? "Error, incorrect type - expected Template."
                : $template->output();
            $output .= $content . $separator;
        }

        return $output;
    }
}
