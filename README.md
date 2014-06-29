cakephp-mysqlup
===============

DBO implementation for the MySQL DBMS with enhanced updateAll() functionality.  Specificially, this adapter allows joins to be used in updateAll().

Requirements
============
    1.) CakePHP 2.3.x or later (Tested up to 5.4.0)
    2.) MySQL 5.1 or later (Tested up to 5.5)
    3.) PHP 5.3 or later (Tested up to 5.5)

Installation
=====
    1.) Copy MysqlUp.php to /app/Model/Datasources
    2.) Set the datasource for your database to "Database/MysqlUp"

Usage
=====
    Use the array key "joins" in your updateAll() conditions array in the same format as you would when using joins in a find().

See CakePHP Documentation for using joins in find() here:
http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#joining-tables
