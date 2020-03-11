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
    course_id INTEGER NOT NULL,
    CONSTRAINT `{$CFG->dbprefix}qs_module_fk_1`
        FOREIGN KEY (`course_id`)
        REFERENCES `{$CFG->dbprefix}lti_link` (`link_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (id)
    ) ENGINE = InnoDB DEFAULT CHARSET=utf8"),

array( "{$CFG->dbprefix}qs_question",
"create table {$CFG->dbprefix}qs_question (
    id INTEGER NOT NULL AUTO_INCREMENT,
    module_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    question_text TEXT NOT NULL,
    date_created DATE NOT NULL,
    status INTEGER,
    anonymous BIT,
    CONSTRAINT `{$CFG->dbprefix}qs_question_fk_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `{$CFG->dbprefix}lti_user` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (id)
    ) ENGINE = InnoDB DEFAULT CHARSET=utf8"),

array( "{$CFG->dbprefix}qs_vote",
"create table {$CFG->dbprefix}qs_vote (
    id INTEGER NOT NULL AUTO_INCREMENT,
    user_id INTEGER,
    question_id INTEGER,
    CONSTRAINT `{$CFG->dbprefix}qs_vote_fk_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `{$CFG->dbprefix}lti_user` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `{$CFG->dbprefix}qs_vote_fk_2`
        FOREIGN KEY (`question_id`)
        REFERENCES `{$CFG->dbprefix}qs_question` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),

array( "{$CFG->dbprefix}qs_user",
"create table {$CFG->dbprefix}qs_user (
    id INTEGER NOT NULL AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    approved BIT DEFAULT 1,
    CONSTRAINT `{$CFG->dbprefix}qs_user_fk_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `{$CFG->dbprefix}lti_user` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8")
);


$DATABASE_UPGRADE = function($oldversion){
    global $CFG, $PDOX;

    if ( $oldversion < 201907070903){
        $sql = "ALTER TABLE {$CFG->dbprefix}qs_question MODIFY anonymous TINYINT";
        echo("Upgrading: ".$sql."<br/>\n");
        error_log("Upgrading: ".$sql);
        $q = $PDOX->queryDie($sql);
    }

    if ( $oldversion < 201907070904){
        $sql = "ALTER TABLE {$CFG->dbprefix}qs_question ADD upvotes INT";
        echo("Upgrading: ".$sql."<br/>\n");
        error_log("Upgrading: ".$sql);
        $q = $PDOX->queryDie($sql);
    }

    if ( $oldversion < 201907070905){
        $sql = "ALTER TABLE {$CFG->dbprefix}qs_question ALTER upvotes SET DEFAULT 0";
        echo("Upgrading: ".$sql."<br/>\n");
        error_log("Upgrading: ".$sql);
        $q = $PDOX->queryDie($sql);
    }
        
    
    return 201907070905;
};