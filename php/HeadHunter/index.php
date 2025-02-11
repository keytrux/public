<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HeadHunter Favorited</title>
    <link rel="stylesheet" href="styles.css"> <!-- Путь к CSS-файлу -->
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<?php
require_once __DIR__ . '/config.php';

function curl($url, $token = null, $page = null)
{
    $data = http_build_query([
        'page' => $page
    ]);

    $url .= "?" . $data;

    $headers = [
        "Content-type: application/json",
        "User-Agent: keytrux/1.0"
    ];

    if ($token) {
        $headers[] = "Authorization: Bearer $token";
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if ($response === false)
    {
        $error = curl_error($ch);
        curl_close($ch);
        return "cURL Error: $error";
    }

    curl_close($ch);

    return json_decode($response, true);
}

$url_favorited = "https://api.hh.ru/vacancies/favorited";
$img_default = "https://www.vbm-medical.de/wp-content/uploads/2021/10/Platzhalter_Image_16zu9.webp";

$count = 1;

$orangeCount = 0;
$greenCount = 0;
$redCount = 0;
$defaultCount = 0;

$favorited_first = curl($url_favorited, TOKEN);

$found = $favorited_first['found'];
$pages = $favorited_first['pages'];

echo "<table>";
echo "<tr>
        <th>№</th>
        <th>Название</th> 
        <th>Зарплата</th> 
        <th>Компания</th> 
        <th>Есть аккредитация</th> 
        <th>Город</th> 
        <th>Адрес</th> 
        <th>Навыки</th> 
        <th>Опыт работы</th>
        <th>График</th>
        <th>Рабочие часы</th>
        <th>Описание</th>
        </tr>";

for($page = 0; $page < $pages; $page++)
{
    $favorited = curl($url_favorited, TOKEN, $page);

    if (isset($favorited['items']))
    {
        foreach ($favorited['items'] as $item)
        {
            if (isset($item['archived']) && $item['archived'] != false)
            {
                continue;
            }

            $id_favorite = isset($item['id']) ? htmlspecialchars($item['id']) : '';
            $nameVacancy = isset($item['name']) ? htmlspecialchars($item['name']) : '';
            $salaryFrom = isset($item['salary']['from']) ? htmlspecialchars($item['salary']['from']) : '';
            $salaryTo = isset($item['salary']['to']) ? htmlspecialchars($item['salary']['to']) : '';
            $currency = isset($item['salary']['currency']) ? htmlspecialchars($item['salary']['currency']) : '';
            $img_company = isset($item['employer']['logo_urls']['original']) ? htmlspecialchars($item['employer']['logo_urls']['original']) : $img_default;
            $company = isset($item['employer']['name']) ? htmlspecialchars($item['employer']['name']) : '';
            $accredited = isset($item['employer']['accredited_it_employer']) && $item['employer']['accredited_it_employer'] === true ? '+' : '';
            $area = isset($item['area']['name']) ? htmlspecialchars($item['area']['name']) : '';
            $address = isset($item['address']['raw']) ? htmlspecialchars($item['address']['raw']) : '';


            $relations = isset($item['relations']) 
            ? (is_array($item['relations']) 
                ? array_map('htmlspecialchars', $item['relations']) 
                : htmlspecialchars($item['relations'])) 
            : '';

            $colorClass = ''; // Инициализация переменной для цвета строки

            if (in_array('got_response', $relations)) 
            {
                $colorClass = 'style="background-color: #ffbf94;"'; // Оранжевый цвет для got_response
                $orangeCount++;
            } 
            elseif (in_array('got_invitation', $relations)) 
            {
                $colorClass = 'style="background-color: #bcf5bc;"'; // Светло-зеленый цвет для got_invitation
                $greenCount++;
            }
            elseif (in_array('got_rejection', $relations)) 
            {
                $colorClass = 'style="background-color: #ff808a;"'; // Светло-красный цвет для got_rejection
                $redCount++;
            }
            else
            {
                $defaultCount++;
            }

            // Формируем строку с зарплатой
            $salaryDisplay = '';

            if ($salaryFrom !== '') {
                $salaryDisplay .= $salaryFrom;
            }

            if ($salaryTo !== '') {
                $salaryDisplay .= ($salaryDisplay !== '' ? ' - ' : '') . $salaryTo;
            }

            if ($currency !== '') {
                $salaryDisplay .= ' ' . $currency;
            }

            $id = isset($item['id']) ? htmlspecialchars($item['id']) : '';

            $vacancy = curl("https://api.hh.ru/vacancies/$id");

            $vacancy_url = $vacancy['alternate_url'];
            
            if (!empty($vacancy['key_skills'])) 
            {
                $key_skills = array_column($vacancy['key_skills'], 'name');
                $skillsString = implode(', ', $key_skills); // Объединяем через запятую
            } 
            else 
            {
                $skillsString = '-'; // Обработка случая, когда массив пуст
            }

            $experience = $vacancy['experience']['name'];

            $description = $vacancy['description'];

            if (!empty($vacancy['work_schedule_by_days'])) 
            {
                $work_days = array_column($vacancy['work_schedule_by_days'], 'name');
                $work_days_string = implode(', ', $work_days); // Объединяем через запятую
            } 
            else 
            {
                $work_days_string = '-'; // Обработка случая, когда массив пуст
            }

            if (!empty($vacancy['working_hours'])) 
            {
                $work_hours = array_column($vacancy['working_hours'], 'name');
                $work_hours_string = implode(', ', $work_hours); // Объединяем через запятую
            } 
            else 
            {
                $work_hours_string = '-'; // Обработка случая, когда массив пуст
            }

            echo "<tr id='row$count' class='collapsed' onclick='toggleRow($count)' $colorClass>
                    <td>" . $count . "</td>
                    <td><a href='$vacancy_url'>" . $nameVacancy . "</a></td>
                    <td>" . $salaryDisplay . "</td>
                    <td><img src='" . $img_company . "' width='20'>  " . $company . "</td>
                    <td>" . $accredited . "</td>
                    <td>" . $area . "</td>
                    <td>" . $address . "</td>
                    <td>" . $skillsString . "</td>
                    <td>" . $experience . "</td>
                    <td>" . $work_days_string . "</td>
                    <td>" . $work_hours_string . "</td>
                    <td><div class='toggle-content collapsed'>" . $description . "<div></td>
                </tr>";

            $count++;

            $vacancyNames[] = $nameVacancy;
            $vacancyUrls[] = $vacancy_url;
            $salariesFrom[] = $salaryFrom;
            $salariesTo[] = $salaryTo;
            $currencies[] = $currency;
        }
    }
}

echo "</table>";

echo "<h4>Количество вакансий: " . $found . "</h4>";

echo "<h4>Отклики:</h4>";
echo "<table style='width:10%;'>";
echo 
    "<tr class='collapsed'>
        <td style='background-color: #ffbf94;'></td>
        <td>" . $orangeCount . "</td>
    </tr>
    <tr class='collapsed'>
        <td style='background-color: #bcf5bc;'></td>
        <td>" . $greenCount . "</td>
    </tr>
    <tr class='collapsed'>
        <td style='background-color: #ff808a;'></td>
        <td>" . $redCount . "</td>
    </tr>
    <tr class='collapsed'>
        <td style='background-color: #b3b3b3;'></td>
        <td>" . $defaultCount . "</td>
    </tr>";
echo "</table>";
?>

<canvas id="salaryChart" width="400" height="200"></canvas>

<script>
    var vacancyNames = <?php echo json_encode($vacancyNames); ?>;
    var salariesFrom = <?php echo json_encode($salariesFrom); ?>;
    var salariesTo = <?php echo json_encode($salariesTo); ?>;
    var vacancyUrls = <?php echo json_encode($vacancyUrls); ?>;
    var currencies = <?php echo json_encode($currencies); ?>;
</script>
</body>
</html>