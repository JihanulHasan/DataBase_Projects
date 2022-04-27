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
(COUNT(SE.Section_Number)/7) AS ClassRoom_Size_7,
(COUNT(SE.Section_Number)/8) AS ClassRoom_Size_8
FROM section_t AS SE 
WHERE 
SE.YEAR='$y' AND Session='$s'
GROUP BY(classsize)
HAVING classsize IS NOT NULL
UNION
     SELECT 'Total' AS classsize, 
     COUNT(SE.Section_Number) AS sections, 
     COUNT(SE.Section_Number) / 7 AS classroom7, 
     COUNT(SE.Section_Number) / 8 AS classroom8
     FROM section_t AS SE
     WHERE SE.Enrolled BETWEEN 1 AND 65 AND SE.YEAR='$year' AND Session='$semester';
  ");
