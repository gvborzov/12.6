<?php
$persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getFullnameFromParts($surname, $name, $patronomyc) {
    return $surname . ' ' . $name . ' ' . $patronomyc;
}

function getPartsFromFullname($fullname) {
    $fullname_parts = explode(' ', $fullname);
    return [
        'surname' => $fullname_parts[0],
        'name' => $fullname_parts[1],
        'patronomyc' => $fullname_parts[2]
    ];
}

function getShortName($fullname) {
    $parts = getPartsFromFullname($fullname);
    return $parts['name'].' '.mb_substr($parts['surname'],0,1).'.';
}


function getGenderFromName($fullname) {
    $parts = getPartsFromFullname($fullname);
    $gender = 0;
    if (mb_substr($parts['patronomyc'], -3) === 'ич') {
        $gender += 1;
    } elseif (mb_substr($parts['patronomyc'], -3) === 'вна') {
        $gender -= 1;
    }
    if (mb_substr($parts['name'], -1) === 'й' || mb_substr($parts['name'], -1) === 'н') {
        $gender += 1;
    } elseif (mb_substr($parts['name'], -1) === 'а') {
        $gender -= 1;
    }
    if (mb_substr($parts['surname'], -1) === 'в') {
        $gender += 1;
    } elseif (mb_substr($parts['surname'], -2) === 'ва') {
        $gender -= 1;
    }
    return ($gender <=> 0);
}


function getGenderDescription($persons_array) {
    $male_count = 0;
    $female_count = 0;
    $unknown_count = 0;
    foreach ($persons_array as $person) {
        $gender = getGenderFromName($person['fullname']);
        if ($gender === 1) {
            $male_count++;
        } elseif ($gender === -1) {
            $female_count++;
        } else {
            $unknown_count++;
        }
    }
    $total_count = $male_count + $female_count + $unknown_count;
    $male_percent = round($male_count / $total_count * 100, 1);
    $female_percent = round($female_count / $total_count * 100, 1);
    $unknown_percent = round($unknown_count / $total_count * 100, 1);
    return "Гендерный состав аудитории:\n---------------------------\nМужчины - {$male_percent}%\nЖенщины - {$female_percent}%\nНе удалось определить - {$unknown_percent}%";
}

function getPerfectPartner($persons_array) {
    $person1 = getPartsFromFullname($persons_array[array_rand($persons_array)]['fullname']);
    $person2 = getPartsFromFullname($persons_array[array_rand($persons_array)]['fullname']);
    $gender1 = getGenderFromName($person1['surname']);
    $gender2 = getGenderFromName($person2['surname']);
    if ($gender1 === 0 || $gender2 === 0 || $gender1 === $gender2) {
      return getPerfectPartner($persons_array);
    }
    $fullname1 = mb_convert_case(getFullnameFromParts(($person1['surname']), ($person1['name']), ($person1['patronymic'])), MB_CASE_TITLE);
    $fullname2 = mb_convert_case(getFullnameFromParts(($person2['surname']), ($person2['name']), ($person2['patronymic'])), MB_CASE_TITLE);
    $percent = mt_rand(50, 100) / 100;
    return (getShortName($fullname1) . ' + ' . getShortName($fullname2) . ' = ' . '♡ Идеально на ' . mt_rand(5000, 10000) / 100 . '% ♡');
}

echo getPerfectPartner($persons_array);