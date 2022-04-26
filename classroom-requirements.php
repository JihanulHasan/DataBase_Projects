<?php


$semester = isset($_POST["semester"]) ? $_POST["semester"] : "";
$semester2 = isset($_POST["semester"]) ? $_POST["semester"] : "Session";
$year = isset($_POST["year"]) ? $_POST["year"] : "";

$con = new mysqli('localhost', 'root', '', 'dbms');





$query = $con->query("
SELECT  
CASE WHEN Enrolled BETWEEN 1 AND 20 then '1-20'
     WHEN Enrolled BETWEEN 21 AND 30 then '21-30'
     WHEN Enrolled BETWEEN 31 AND 35 then '31-35'
     WHEN Enrolled BETWEEN 36 AND 40 then '36-40'
     WHEN Enrolled BETWEEN 41 AND 50 then '41-50'
     WHEN Enrolled BETWEEN 51 AND 54 then '51-54'
     WHEN Enrolled BETWEEN 55 AND 64 then '55-64'
     WHEN Enrolled BETWEEN 65 AND 124 then '65-124'
     WHEN Enrolled BETWEEN 125 AND 168 then '125-168'
     END AS classsize,
COUNT(SE.Section_Number) AS Sections,
(COUNT(SE.Section_Number)/14) AS ClassRoom_Size_7,
(COUNT(SE.Section_Number)/16) AS ClassRoom_Size_8
FROM section_t AS SE 
WHERE 
SE.YEAR='$year' AND Session='$semester'
GROUP BY(classsize)
HAVING classsize IS NOT NULL
UNION
     SELECT 'Total' AS classsize, 
     COUNT(SE.Section_Number) AS sections, 
     COUNT(SE.Section_Number) / 14 AS classroom6, 
     COUNT(SE.Section_Number) / 16 AS classroom7
     FROM section_t AS SE
     WHERE SE.Enrolled BETWEEN 1 AND 65 AND SE.YEAR='$year' AND Session='$semester';
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js" integrity="sha512-R/QOHLpV1Ggq22vfDAWYOaMd5RopHrJNMxi8/lJu8Oihwi4Ho4BRFeiMiCefn9rasajKjnx9/fTQ/xkWnkDACg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<body>
    <div class="container">
        <?php include 'shared-components/sidebar.php'; ?>
        <div class="main_contents">
            <div class="row mt-5">
                <div class=" d-flex align-items-center justify-content-center">
                    <form  method="POST" >
                        <div class="">
                        <select class="dropDown_bar shadow me-5" name="semester" id="semester">
                               
                                <option value="Summer">Summer</option>
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
            <div class="row mt-5 ps-5" >
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <h4 class="text-primary fw-bolder lh-lg pt-5 pb-3 text-uppercase">CLASS ROOM REQUIREMENTS OF <?php echo $semester2." ". $year?></h4>
                    <table class="table table-striped table-info">
                        <thead>
                            <tr>

                                <?php
                                   
                                    while ($fieldinfo = $query->fetch_field()) {
                                        echo '<th scope="col">'.$fieldinfo->name.'</th>';
                                        }
                                   
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($query->num_rows > 0) {
                                while ($row = $query->fetch_row()) {
                                    echo '<tr>';
                                    for ($i=0; $i < sizeof($row); $i++) { 
                                        
                                        echo '<td>' . $row[$i] . '</td>';
                                    }
                                    echo '</tr>';
                                }
                            } else {
                                echo "0 results";
                            }

                            mysqli_close($con);
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-5 ps-5">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <h4 class="text-primary fw-bolder lh-lg pt-5 pb-3">CLASS ROOM REQUIREMENTS GRAPH & CHARTS</h4>
                    <div class="w-50">
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
            $classroomSeven[] = $data['ClassRoom_Size_7'];
            $classsize[] = $data['classsize'];
        }

        array_pop($classroomSeven);
        array_pop($classsize);

        ?>
        const labels = <?php echo json_encode($classsize) ?>;


        const data = {
            labels: labels,
            datasets: [{
                label: 'My First Dataset',
                data: <?php echo json_encode($classroomSeven) ?>,
                backgroundColor: [
                    '#CB4335', '#1F618D', '#F1C40F', '#27AE60', '#884EA0', '#D35400','#420fe7'
                ],
                borderColor: [
                    'white'
                ],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'pie',
            data: data,
            options: {
                scales: {

                }
            },
        };


        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
</body>

</html>