<?php 
    function getConnection(){
        $DB_DNS = "mysql:host=localhost;dbname=QR_CODE";
        $DB_USER = "ErnestPenaJr";
        $DB_PASSWORD = "$268RedDragons";
        $conn = new PDO($DB_DNS, $DB_USER, $DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    if(isset($_POST["method"])) {
        $method = $_POST["method"];
        if($method=="addGrantData") {addGrantData();};
        if($method=="addCategoryData") {addCategoryData();};
        if($method=="addCFSData") {addCFSData();};
        if($method=="addProjectReportsData") {addProjectReportsData();};
        if($method=="addStatusTypeData") {addStatusTypeData();};
        if($method=="getGrantData") {getGrantData();};
    }
    function addCategoryData(){}
    function addCFSData(){}
    function addProjectReportsData(){}
    function addStatusTypeData(){}

    function addGrantData(){
        $conn = getConnection();
        $date = date('Y-m-d',strtotime($_POST['servicedate']));
        $facebook = $_POST['facebook'];
        $youtube = $_POST['youtube'];
        $website = $_POST['website'];
        $audience = $_POST['audience'];
        $service = $_POST['service'];

        //verify that the date is not already in the database
        $statement = $conn->prepare("SELECT * FROM SERVICE_STATS WHERE SERVICEDATE = :date AND SERVICETIME = :service");
        $statement->execute([':date' => $date, ':service' => $service]);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(count($results) > 0){ 
            header('Content-Type: application/json');
            $response[0]['message'] = 'This date already exists in the database';
            $response[0]['messageID'] = '0';
            $json = json_encode(array('items' => $response));
        }else{
            $statement = $conn->prepare("INSERT INTO SERVICE_STATS (SERVICEDATE,FACEBOOK,YOUTUBE,WEBSITE,AUDIENCE,SERVICETIME) VALUES (:servicedate,:facebook,:youtube,:website,:audience,:serviceTime)");
            $statement->execute([':servicedate' => $date, ':facebook' => $facebook,':youtube' => $youtube,':website' => $website,':audience' => $audience,':serviceTime' => $service]);
            header('Content-Type: application/json');
            $response[0]['message'] = 'Data added successfully';
            $response[0]['messageID'] = '1';
            $json = json_encode(array('items' => $response));
        }
        echo $json;
    };
    function getGrantData(){
        $conn = getConnection();
        $statement = $conn->prepare("SELECT * FROM SUBMISSIONS");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        $json = json_encode(array('data' => $results));
        echo $json;
    };
?>