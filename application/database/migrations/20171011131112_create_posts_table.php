<?php

class Migration_create_posts_table extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
                'unsigned' => TRUE,
                'null' => FALSE

            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE

            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'picture' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE

            ],
            'visible' => [
                'type' => 'TINYINT',
                'null' => TRUE
            ],
            'order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => TRUE
            ],
            'special' => [
                'type' => 'TINYINT',
                'default' => 0,
                'null' => TRUE
            ],
            'seo_params' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'admin_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ]
        ]);

        $this->dbforge->add_field('published_at DATETIME DEFAULT CURRENT_TIMESTAMP ');
        $this->dbforge->add_field('created_at DATETIME DEFAULT CURRENT_TIMESTAMP ');
        $this->dbforge->add_field('updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('posts');
    }

    public function down()
    {
        $this->dbforge->drop_table('posts');
    }

}