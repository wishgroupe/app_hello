DROP TABLE IF EXISTS subscriber ;

CREATE TABLE IF NOT EXISTS subscriber (
    id int(11) NOT NULL auto_increment,
    creationDate datetime NOT NULL,
    clientToken varchar(250) NOT NULL DEFAULT '',
    clientName varchar(250) NOT NULL DEFAULT '',
    expirationDate timestamp NOT NULL DEFAULT 0,
    appToken varchar(250) NOT NULL DEFAULT '',
    PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE INDEX appToken ON subscriber (appToken ASC);
CREATE INDEX clientToken ON subscriber (clientToken ASC);
