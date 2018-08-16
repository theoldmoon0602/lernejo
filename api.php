<?php

const DBJSON="./db.json";

function load() {
  $a = file_get_contents(DBJSON);
  if ($a) {
    return json_decode($a, true);
  }

  return [];
}

function store($data) {
  file_put_contents(DBJSON, json_encode($data, JSON_UNESCAPED_UNICODE));
}

function add_question($data, $question, $correct) {
  $keys = array_keys($data);
  $id = max($keys) + 1;

  $data[$id] = [
    'question' => $question,
    'correct' => $correct,
  ];

  return $data;
}

function remove_question($data, $id) {
  unset($data[$id]);
  return $data;
}

function edit_question($data, $id, $question, $correct) {
  $data[$id] = [
    'question' => $question,
    'correct' => $correct,
  ];

  return $data;
}







if (isset($_GET['query'])) {
  $query = $_GET['query'];
  if ($query === 'add' && isset($_GET['q']) && isset($_GET['c'])) {
    $q = $_GET['q'];
    $c = $_GET['c'];

    $data = load();
    $data = add_question($data, $q, $c);
    store($data);

    $id = max(array_keys($data));

    echo json_encode(['id' => $id]); 
  }
  if ($query === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $data = load();
    $data = remove_question($data, $id);
    store($data);

    echo json_encode([]); 
  }
  if ($query === 'edit' && isset($_GET['id']) && isset($_GET['q']) && isset($_GET['c'])) {
    $q = $_GET['q'];
    $c = $_GET['c'];
    $id = $_GET['id'];

    $data = load();
    $data = edit_question($data, $id, $q, $c);
    store($data);

    echo json_encode([]); 
  }
  if ($query === 'get') {
    $data = load();
    echo json_encode($data, JSON_UNESCAPED_UNICODE); 
  }
}
