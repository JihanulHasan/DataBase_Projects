CASE 
        WHEN Capacity BETWEEN 1 AND 20 THEN '20'
          WHEN Capacity BETWEEN 21 AND 30 THEN '30'
          WHEN Capacity BETWEEN 31 AND 35 THEN '35'
          WHEN Capacity BETWEEN 36 AND 40 THEN '40'
          WHEN Capacity BETWEEN 41 AND 50 THEN '50'
          WHEN Capacity BETWEEN 51 AND 54 THEN '54'
          WHEN Capacity BETWEEN 55 AND 64 THEN '64'
          WHEN Capacity BETWEEN 65 AND 124 THEN '124'
          WHEN Capacity BETWEEN 125 AND 168 THEN '168'
          WHEN Capacity THEN Capacity
      END AS classsize,
  COUNT(DISTINCT(section_t.Room_ID)) AS IUB_resources,
  ROUND(COUNT(CASE WHEN Enrolled > 0 THEN 1 END) / 12) AS $semester2,
  ROUND(COUNT(DISTINCT(section_t.Room_ID)) - COUNT(CASE WHEN Enrolled > 0 THEN 1 END) / 12, 1) AS Difference
  FROM section_t
  LEFT JOIN room_t ON section_t.Room_ID = room_t.Room_Id
  WHERE Session = '$semester' AND YEAR = '$year' OR Enrolled = 0
  GROUP BY classsize
  HAVING classsize IS NOT NULL
  UNION
  SELECT 
    'Total',
      COUNT(DISTINCT(section_t.Room_ID)) AS IUB_resources,
      ROUND(COUNT(CASE WHEN Enrolled > 0 THEN 1 END) / 12, 1) AS semester,
      ROUND(COUNT(DISTINCT(section_t.Room_ID)) - COUNT(CASE WHEN Enrolled > 0 THEN 1 END) / 12, 1) AS Difference
  FROM section_t
  LEFT JOIN room_t ON section_t.Room_ID = room_t.Room_Id
  WHERE Session = '$semester' AND YEAR = '$year' OR Enrolled = 0 AND Capacity != 0;
  ");
