<?php

class Migration_create_admins_table extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
                'unsigned' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'family' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE
            ),
            'password' => [
                'type' => 'TEXT',
                'null' => FALSE
            ],
            'email' => [
                'type' => 'TEXT',
                'null' => FALSE
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'picture' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 0,
                'null' => TRUE

            ],
            'status' => [
                'type' => 'ENUM("disabled","normal")',
                'default' => 'normal',
                'null' => TRUE,
            ],
            'type' => [
                'type' => 'ENUM("master", "admin" ,"author")',
                'default' => 'author',
                'null' => FALSE
            ],
            'login_attempts' => [
                'type' => 'INT',
                'default' => 0
            ],
            'login_date' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],
            'activation_code' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'activation_code_expire' => [
                'type' => 'DATE',
                'null' => TRUE
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint' => '4',
                'default' => 0,
                'null' => TRUE
            ]

        ]);


        $this->dbforge->add_field('created_at DATETIME DEFAULT CURRENT_TIMESTAMP ');
        $this->dbforge->add_field('updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('admins');
    }

    public function down()
    {
        $this->dbforge->drop_table('admins');
    }

}