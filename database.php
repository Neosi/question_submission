<?php

$DATABASE_UNINSTALL = array(
"drop table if exists {$CFG->dbprefix}qs_question",
"drop table if exists {$CFG->dbprefix}qs_module",
"drop table if exists {$CFG->dbprefix}qs_vote",
"drop table if exists {$CFG->dbprefix}qs_user"
);

$DATABASE_INSTALL = array(

array( "{$CFG->dbprefix}qs_question",
"CREATE TABLE {$CFG->dbprefix}qs_question (
    id INTEGER NOT NULL AUTO_INCREMENT,
    module_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    question_text TEXT NOT NULL,
    date_created DATE NOT NULL,
    upvotes INT DEFAULT 0,
    link_id INT,
    status INTEGER,
    anonymous BIT,
    CONSTRAINT `{$CFG->dbprefix}qs_question_fk_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `{$CFG->dbprefix}lti_user` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (id)
    ) ENGINE = InnoDB DEFAULT CHARSET=utf8"),

array( "{$CFG->dbprefix}qs_vote",
"CREATE TABLE {$CFG->dbprefix}qs_vote (
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
"CREATE TABLE {$CFG->dbprefix}qs_user (
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

    if ( $oldversion < 201907070905){
        $sql = "ALTER TABLE {$CFG->dbprefix}qs_question ADD upvotes INT default 0";
        echo("Upgrading: ".$sql."<br/>\n");
        error_log("Upgrading: ".$sql);
        $q = $PDOX->queryDie($sql);
    }

    if ( $oldversion < 201907070906){
        $sql = "drop table if exists {$CFG->dbprefix}qs_module";
        echo("Upgrading: ".$sql."<br/>\n");
        error_log("Upgrading: ".$sql);
        $q = $PDOX->queryDie($sql);
    }

    if ( $oldversion < 201907070907){ 
        $sql = "ALTER TABLE {$CFG->dbprefix}qs_question ADD link_id INT";
        echo("Upgrading: ".$sql."<br/>\n");
        error_log("Upgrading: ".$sql);
        $q = $PDOX->queryDie($sql);
    }
    
    return 201907070907;
};