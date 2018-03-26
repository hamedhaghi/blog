<?php

class Migration_create_posts_tags_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
                'unsigned' => TRUE,
                'null' => FALSE
            ],
            'post_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ],
             'tag_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ]


        ]);
        $this->dbforge->add_field('created_at DATETIME DEFAULT CURRENT_TIMESTAMP ');
        $this->dbforge->add_field('updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE ON UPDATE CASCADE');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('posts_tags');
    }

    public function down() {
        $this->dbforge->drop_table('posts_tags');
    }

}