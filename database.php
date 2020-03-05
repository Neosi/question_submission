<?php

$DATABASE_UNINSTALL = array(
"drop table if exists {$CFG->dbprefix}qs_question",
"drop table if exists {$CFG->dbprefix}qs_module",
"drop table if exists {$CFG->dbprefix}qs_vote",
"drop table if exists {$CFG->dbprefix}qs_user"
);

$DATABASE_INSTALL = array(

array( "{$CFG->dbprefix}qs_module",
"create table {$CFG->dbprefix}qs_module (
    id INTEGER NOT NULL AUTO_INCREMENT,
    course_id INTEGER
    ) ENGINE = InnoDB DEFAULT CHARSET=utf8"),

array( "{$CFG->dbprefix}qs_question",
"create table {$CFG->dbprefix}qs_question (
    id INTEGER NOT NULL AUTO_INCREMENT,
    module_id INTEGER,
    question_text TEXT,
    time_created DATETIME,
    status INTEGER,
    anonymous INTEGER,
    user_id INTEGER,
    ) ENGINE = InnoDB DEFAULT CHARSET=utf8"),

array( "{$CFG->dbprefix}qs_vote",
"create table {$CFG->dbprefix}lms_tools_status (
    id INTEGER NOT NULL AUTO_INCREMENT,
    user_id INTEGER,
    question_id INTEGER,
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),

array( "{$CFG->dbprefix}qs_user",
"create table {$CFG->dbprefix}lms_tools_status (
    id INTEGER NOT NULL AUTO_INCREMENT,
    approved DEFAULT 1,
) ENGINE = InnoDB DEFAULT CHARSET=utf8")
);

$DATABASE_UPGRADE = function($oldversion) {
    global $CFG, $PDOX;
    return 201804301336;

};