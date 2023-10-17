<?php 

(int)$permissions = [
    'read' => 1,
    'update' => 2,
    'edit' => 3,
    'delete' => 4
];

$user = $permissions["read"];

$user |= $permissions['delete'];

if($user & $permissions['delete']) {
    echo "Pode";
}
