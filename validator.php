<?php
function trimData($tab)
{
    if (is_array($tab)) {
        foreach ($tab as $key => $value) {
            $value = trim($value);
            $value = strip_tags($value);
            $tab[$key] = htmlspecialchars($value);
        }
        return $tab;
    }
}

function checkData($tab)
{
    $tab = trimData($tab);
    $result = array();
    foreach ($tab as $key => $value) {
        $value = trim($value);
        //GENERAL
        if ($value === "") {
            $result[$key] = "Le champs " . $key . "  ne peut pas être vide";
        }

        //SPECIFIQUE  
        if ($key == "title" && !isset($result[$key])) {
            if (strlen($value) < 5) {
                $result[$key] = "Le titre doit faire au moins 5 caratères !";
            }
            if (strlen($value) > 20) {
                $result[$key] = "Le titre doit faire au maxium 20 caratères !";
            }
        }

        if ($key == "description" && !isset($result[$key])) {
            if (strlen($value) < 10) {
                $result[$key] = "La description doit faire au moins 10 caratères !";
            }
            if (strlen($value) > 50) {
                $result[$key] = "La description doit faire au maximum 50 caratères !";
            }
        }
    }
    return $result;
}
