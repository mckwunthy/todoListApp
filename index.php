<?php
require_once("validator.php");

if (isset($_POST) && !empty($_POST)) {
    // var_dump($_POST);
    //validation
    $data = [];
    $data["title"] = $_POST["td-title"];
    $data["description"] = $_POST["td-description"];

    $error = checkData($data);
}

//save new list into taskList
if (isset($_POST) && !empty($_POST) && empty($error)) {
    //pas d'erreur --> on sauvegarde les infos
    $result = trimData($data);
    $list = [];
    $list["title"] = $result["title"];
    $list["description"] = $result["description"];
    $list["status"] = "";

    //get list from taskList.json
    $listPath = "taskList.json";
    $file = file_get_contents($listPath, true);
    $fileList = json_decode($file, true);
    //add new task at the end tof array
    $taskCount = count($fileList);
    $fileList[$taskCount] = $list;
    $fileList_json = json_encode($fileList);

    file_put_contents($listPath, $fileList_json);

    //redirection to avoid reload data
    header("location: index.php");
}

//delete taskList
if (isset($_GET) && !empty($_GET)) {
    //pas d'erreur --> on mets à jours les infos
    $data = [];
    $data["id"] = $_GET["deteleTask"];
    $result = trimData($data);

    //get list from taskList.json
    $listPath = "taskList.json";
    $file = file_get_contents($listPath, true);
    $fileList = json_decode($file, true);

    // var_dump($fileList);
    //delete task who has id $result["id"]
    unset($fileList[$result["id"]]);

    //on reclasse les elements
    $i = 0;
    $newFileList = [];
    foreach ($fileList as $key => $value) {
        $newFileList[$i] = $value;
        $i++;
    }

    $newFileList_json = json_encode($newFileList);
    file_put_contents($listPath, $newFileList_json);

    //redirection to avoid reload data
    // header("location: index.php");
}
//updat taskList
if (isset($_POST["validate"]) && !empty($_POST["validate"])) {
    //pas d'erreur --> on mets à jours les infos
    $data = [];
    $data["id"] = $_POST["selecTask"];
    $data["status"] = $_POST["status"];
    $result = trimData($data);

    //get list from taskList.json
    $listPath = "taskList.json";
    $file = file_get_contents($listPath, true);
    $fileList = json_decode($file, true);


    //updat task who has id $result["id"]
    $fileList[$result["id"]]["status"] = $result["status"];

    $newFileList_json = json_encode($fileList);
    file_put_contents($listPath, $newFileList_json);

    //redirection to avoid reload data
    header("location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoList</title>
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <div id="app">
        <h1>todolist</h1>
        <div class="todolist">
            <div class="form">
                <form action="index.php" method="POST">
                    <div class="first-line">
                        <input type="text" name="td-title" id="td-title" placeholder="tilte" required>
                    </div>
                    <div class="error error-title">
                        <?php
                        echo isset($error["title"]) && !empty($error["title"]) ? $error["title"] : null;
                        ?>
                    </div>
                    <div class="second-line">
                        <input type="text" name="td-description" id="td-description" placeholder="descripton">
                    </div>
                    <div class="error error-description">
                        <?php
                        echo isset($error["description"]) && !empty($error["description"]) ? $error["description"] : null;
                        ?>
                    </div>
                    <div class="fird-line">
                        <input type="submit" value="enregistrer" id="submit-task">
                    </div>
                </form>
            </div>
            <div class="list">
                <?php
                //get list from taskList.json and display it
                $listPath = "taskList.json";
                $file = file_get_contents($listPath, true);
                $fileList = json_decode($file, true);
                // var_dump($fileList[1]);
                if (isset($fileList) && !empty($fileList)) {
                    for ($i = 0; $i < count($fileList); $i++) {
                        // var_dump($fileList);
                ?>
                        <div class="display-list">
                            <div class="display-list-title"> <?php echo $fileList[$i]["title"]; ?></div>
                            <div class="display-list-description">Discription : <?php echo $fileList[$i]["description"] ?></div>
                            <div class="display-list-status">
                                <form action="index.php" method="POST">
                                    <label for="status">Status : </label>
                                    <label for="termine">Termine : </label>
                                    <input type="radio" name="status" value="termine" <?php echo $fileList[$i]["status"] == "termine" ? "checked" : null; ?>>
                                    <label for="termine">En cours : </label>
                                    <input type="radio" name="status" value="encours" <?php echo $fileList[$i]["status"] == "encours" ? "checked" : null; ?>>
                                    <input type="hidden" name="selecTask" value="<?php echo $i; ?>">
                                    <input type="submit" value="validate" name="validate" id="submit-status">
                                </form>
                                <div class="delete-list">
                                    <form action="index.php" method="GET">
                                        <input type="hidden" name="deteleTask" value="<?php echo $i; ?>">
                                        <input type="submit" value="delete">
                                    </form>
                                </div>
                            </div>
                        </div>
                <?php

                    }
                } else {
                    echo '<div class="aucune-infos">
                    aucune tâches enregistrée !
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>