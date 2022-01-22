<?php

use Illuminate\Support\Facades\DB;

function testHelper(){
    return 'Test Helper';
}

function userMenu(){
    $mnGroups = DB::table('v_usermenus')
                ->select('menugroup', 'groupname', 'groupicon')
                ->distinct()
                // ->where('email', Auth::user()->email)
                ->orWhere('id', Auth::user()->id)
                ->get();
    return $mnGroups;
}

function userSubMenu(){
    $mnGroups = DB::table('v_usermenus')
                ->select('menugroup', 'route', 'description')
                ->distinct()
                // ->where('email', Auth::user()->email)
                ->orWhere('id', Auth::user()->id)
                ->get();
    return $mnGroups;
}

function insertOrUpdate(array $rows, $table){
    $first = reset($rows);

    $columns = implode(
        ',',
        array_map(function ($value) {
            return "$value";
        }, array_keys($first))
    );

    $values = implode(',', array_map(function ($row) {
            return '('.implode(
                ',',
                array_map(function ($value) {
                    return '"'.str_replace('"', '""', $value).'"';
                }, $row)
            ).')';
    }, $rows));

    $updates = implode(
        ',',
        array_map(function ($value) {
            return "$value = VALUES($value)";
        }, array_keys($first))
    );

    $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

    return \DB::statement($sql);
}