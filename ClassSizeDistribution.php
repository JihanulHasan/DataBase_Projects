<?php


$semester = isset($_POST["semester"]) ? $_POST["semester"] : "";
$semester2 = isset($_POST["semester"]) ? $_POST["semester"] : "Session";
$year = isset($_POST["year"]) ? $_POST["year"] : "";

$con = new mysqli('localhost', 'root', '', 'dbms');





$query = $con->query("
SELECT
       CASE WHEN Enrolled BETWEEN 1 AND 10 THEN '1-10'
       WHEN Enrolled BETWEEN 11 AND 20 THEN '11-20'
       WHEN Enrolled BETWEEN 21 AND 30 THEN '21-30'
       WHEN Enrolled BETWEEN 31 AND 35 THEN '31-35'
       WHEN Enrolled BETWEEN 36 AND 40 THEN '36-40'
       WHEN Enrolled BETWEEN 41 AND 50 THEN '41-50'
       WHEN Enrolled BETWEEN 51 AND 55 THEN '51-55'
       WHEN Enrolled BETWEEN 56 AND 60 THEN '56-60'
       WHEN Enrolled > 60 THEN '60+' 
       END AS Enrollment,
       COUNT(CASE WHEN department_t.School_Title='SBE' THEN 'SBE' END) AS SBE,
       COUNT(CASE WHEN department_t.School_Title='SELS' THEN 'SELS' END) AS SELS,
       COUNT(CASE WHEN department_t.School_Title='SETS' THEN 'SETS' END) AS SETS,
       COUNT(CASE WHEN department_t.School_Title='SLASS' THEN 'SLASS' END) AS SLASS,
       COUNT(CASE WHEN department_t.School_Title='SPPH' THEN 'SPPH' END) AS SPPH,
       COUNT(section_t.Course_ID) AS TOTAL
       FROM section_t, department_t
       WHERE Session = '$semester' 
       AND YEAR = '$year' 
       AND section_t.Department_ID=department_t.Department_ID
       GROUP BY Enrollment
       HAVING Enrollment IS NOT NULL;
  ");




?>



<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Bebas+Neue&family=Karla:wght@200;300;400&family=Oswald:wght@200;300;400;500;600;700&family=Righteous&display=swap" rel="stylesheet">
    <title> Student Enrollment Analysis System</title>
</head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body>
    <div class="container">
        <?php include 'sidebar.php' ;
         color() ?>
        <div class="main_contents">
            <div class="row mt-5">
                <div class=" d-flex align-items-center justify-content-center">
                    <form method="POST">
                        <div class="">
                            <select class="dropDown_bar shadow me-5" name="semester" id="semester">                                <option value="Summer">Summer</option>
                                <option value="Spring">Spring</option>
                                <option value="Autumn">Autumn</option>
                            </select>
                            <select class="dropDown_bar shadow me-5" name="year" id="year">
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                            </select>
                            <button class="btn btn-primary shadow-lg rounded-pill" type="submit"> submit </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-5 ps-5">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <h4 class="text-primary fw-bolder lh-lg pt-5 pb-3 text-uppercase">class size distributions <?php echo $semester2 . " " . $year ?></h4>
                    <table class="table table-striped table-info">
                        <thead>
                            <tr>
                                <?php
                                while ($fieldinfo = $query->fetch_field()) {
                                    echo '<th scope="col">' . $fieldinfo->name . '</th>';
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($query->num_rows > 0) {
                                while ($row = $query->fetch_row()) {
                                    echo '<tr>';
                                    for ($i = 0; $i < sizeof($row); $i++) {

                                        echo '<td>' . $row[$i] . '</td>';
                                    }
                                    echo '</tr>';
                                }
                            } 

                            mysqli_close($con);
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-5 ps-5">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <h4 class="text-primary fw-bolder lh-lg pt-5 pb-3 text-uppercase">class size distributions GRAPH & CHARTS</h4>
                    <div class="w-75">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->


    <script>
        <?php

        foreach ($query as $data) {
            $Enrollment[] = $data['Enrollment'];
            $SBE[] = $data['SBE'];
            $SELS[] = $data['SELS'];
            $SETS[] = $data['SETS'];
            $SLASS[] = $data['SLASS'];
            $SPPH[] = $data['SPPH'];
            $TOTAL[] = $data['TOTAL'];
        }


        ?>

      

        // setup 
    const data = {
      labels: <?php echo json_encode($Enrollment) ?>
,
      datasets: [{
        label: 'SBE',
        data: <?php echo json_encode($SBE) ?>,
        backgroundColor: '#061d32',    
        borderWidth: 1
      },
      {
        label: 'SELS',
        data: <?php echo json_encode($SELS) ?>,
        backgroundColor: '#b2d3d1',
        borderWidth: 1
      },
      {
        label: 'SETS',
        data: <?php echo json_encode($SETS) ?>,
        backgroundColor:'#D35400',
        borderWidth: 1
      },
      {
        label: 'SLASS',
        data: <?php echo json_encode($SLASS) ?>,
        backgroundColor: '#27AE60', 
       
        borderWidth: 1
      },
      {
        label: 'SPPH',
        data: <?php echo json_encode($SPPH) ?>,
        backgroundColor:'#884EA0',
        
        borderWidth: 1
      },
      {
        label: 'TOTAL',
        data: <?php echo json_encode($TOTAL) ?>,
        backgroundColor:'#dfe2db',
        borderColor:'##3a576e',
        borderWidth: 2,
        type:'line'
      }
    ]
    };

    // config 
    const config = {
      type: 'bar',
      data,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    };
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
</body>

</html>