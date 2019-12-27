<?php
  class GeneratorParameters
  {
    private $nom_section;
    private $nom_technique;
    private $style_specifique;
    private $script_specifique;
    private $options;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->nom_section       = '';
      $this->nom_technique     = '';
      $this->nom_head          = '';
      $this->style_specifique  = '';
      $this->script_specifique = '';
      $this->options           = array();
    }

    // Constructeur de l'objet GeneratorParameters en fonction des données
    // -> il faut passer une variable $data
    public static function withData($data)
    {
      $generatorParameters = new self();
      $generatorParameters->fill($data);

      return $generatorParameters;
    }

    protected function fill ($data)
    {
      if (isset($data['nom_section']))
        $this->nom_section       = $data['nom_section'];

      if (isset($data['nom_technique']))
        $this->nom_technique     = $data['nom_technique'];

      if (isset($data['nom_head']))
        $this->nom_head          = $data['nom_head'];

      if (isset($data['style_specifique']))
        $this->style_specifique  = $data['style_specifique'];

      if (isset($data['script_specifique']))
        $this->script_specifique = $data['script_specifique'];

      if (isset($data['options']))
        $this->options           = $data['options'];
    }

    // getters et setters pour l'objet GeneratorParameters
    // Nom fonctionnel
    public function setNom_section($nom_section)
    {
      $this->nom_section = $nom_section;
    }

    public function getNom_section()
    {
      return $this->nom_section;
    }

    // Nom technique
    public function setNom_technique($nom_technique)
    {
      $this->nom_technique = $nom_technique;
    }

    public function getNom_technique()
    {
      return $this->nom_technique;
    }

    // Nom Head
    public function setNom_head($nom_head)
    {
      $this->nom_head = $nom_head;
    }

    public function getNom_head()
    {
      return $this->nom_head;
    }

    // Style spécifique
    public function setStyle_specifique($style_specifique)
    {
      $this->style_specifique = $style_specifique;
    }

    public function getStyle_specifique()
    {
      return $this->style_specifique;
    }

    // Script spécifique
    public function setScript_specifique($script_specifique)
    {
      $this->script_specifique = $script_specifique;
    }

    public function getScript_specifique()
    {
      return $this->script_specifique;
    }

    // Options
    public function setOptions($options)
    {
      $this->options = $options;
    }

    public function getOptions()
    {
      return $this->options;
    }
  }

  class GeneratorOptions
  {
    private $option;
    private $checked;
    private $titre;
    private $categorie;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->option    = '';
      $this->checked   = '';
      $this->titre     = '';
      $this->categorie = '';
    }

    // Constructeur de l'objet GeneratorOptions en fonction des données
    // -> il faut passer une variable $data
    public static function withData($data)
    {
      $generatorOptions = new self();
      $generatorOptions->fill($data);

      return $generatorParameters;
    }

    protected function fill ($data)
    {
      if (isset($data['option']))
        $this->option    = $data['option'];

      if (isset($data['checked']))
        $this->checked   = $data['checked'];

      if (isset($data['titre']))
        $this->titre     = $data['titre'];

      if (isset($data['categorie']))
        $this->categorie = $data['categorie'];
    }

    // getters et setters pour l'objet GeneratorOptions
    // Nom option
    public function setOption($option)
    {
      $this->option = $option;
    }

    public function getOption()
    {
      return $this->option;
    }

    // Case cochée
    public function setChecked($checked)
    {
      $this->checked = $checked;
    }

    public function getChecked()
    {
      return $this->checked;
    }

    // Titre
    public function setTitre($titre)
    {
      $this->titre = $titre;
    }

    public function getTitre()
    {
      return $this->titre;
    }

    // Catégorie
    public function setCategorie($categorie)
    {
      $this->categorie = $categorie;
    }

    public function getCategorie()
    {
      return $this->categorie;
    }
  }
?>