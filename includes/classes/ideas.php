<?php
  class Ideas
  {
    private $id;
    private $subject;
    private $date;
    private $author;
    private $content;
    private $status;
    private $developper;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id = 0;
      $this->subject    = '';
      $this->date       = '';
      $this->author     = '';
      $this->content    = '';
      $this->status     = '';
      $this->developper = '';
    }

    // Constructeur de l'objet Ideas en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $ideas = new self();
      $ideas->fill($data);

      return $ideas;
    }

    protected function fill ($data)
    {
      
       /******\
     /         \
    |    !!    |
    \         /
     \******/

      if (isset($data['id']))
        $this->id         = $data['id'];

      if (isset($data['subject']))
        $this->subject    = $data['subject'];

      if (isset($data['date']))
        $this->date       = $data['date'];

      if (isset($data['author']))
        $this->author     = $data['author'];

      if (isset($data['content']))
        $this->content    = $data['content'];

      if (isset($data['status']))
        $this->status     = $data['status'];

      if (isset($data['developper']))
        $this->developper = $data['developper'];
    }

    // getters et setters pour l'objet Ideas
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Sujet
    public function setSubject($subject)
    {
      $this->subject = $subject;
    }

    public function getSubject()
    {
      return $this->subject;
    }

    // Date
    public function setDate($date)
    {
      $this->date = $date;
    }

    public function getDate()
    {
      return $this->date;
    }

    // Auteur
    public function setAuthor($author)
    {
      $this->author = $author;
    }

    public function getAuthor()
    {
      return $this->author;
    }

    // Contenu
    public function setContent($content)
    {
      $this->content = $content;
    }

    public function getContent()
    {
      return $this->content;
    }

    // Status
    public function setStatus($status)
    {
      $this->status = $status;
    }

    public function getStatus()
    {
      return $this->status;
    }

    // Développeur
    public function setDevelopper($developper)
    {
      $this->developper = $developper;
    }

    public function getDevelopper()
    {
      return $this->developper;
    }
  }
?>