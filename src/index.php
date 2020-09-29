<?php

// Déclaration des classes

class Produit
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    public $nom;

    /**
     * @var string
     */
    public $description;

    /**
     * @var float
     */
    public $prix;

    public function __construct(
        ?int $id = null,
        ?string $nom = null,
        ?string $description = null,
        ?float $prix = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->prix = $prix;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return float|null
     */
    public function getPrix(): ?float
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }

    public function __toString(): string
    {
        return "Produit n°" . $this->id . " - " . $this->nom . " (" . $this->prix . " €)";
    }
}

class Utilisateur
{
    /**
     * @var string
     */
    protected $nomUtilisateur;

    /**
     * @var string
     */
    protected $motDePasse;

    /**
     * @var string
     */
    protected $nomComplet;

    /**
     * Utilisateur constructor.
     * @param string|null $nomUtilisateur
     * @param string|null $motDePasse
     * @param string|null $nomComplet
     */
    public function __construct(
        ?string $nomUtilisateur = null,
        ?string $motDePasse = null,
        ?string $nomComplet = null
    ) {
        $this->nomUtilisateur = $nomUtilisateur;
        $this->motDePasse = $motDePasse;
        $this->nomComplet = $nomComplet;
    }

    /**
     * @return string
     */
    public function getNomUtilisateur(): string
    {
        return $this->nomUtilisateur;
    }

    /**
     * @param string $nomUtilisateur
     */
    public function setNomUtilisateur(string $nomUtilisateur): void
    {
        $this->nomUtilisateur = $nomUtilisateur;
    }

    /**
     * @return string
     */
    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }

    /**
     * @param string $motDePasse
     */
    public function setMotDePasse(string $motDePasse): void
    {
        $this->motDePasse = $motDePasse;
    }

    /**
     * @return string
     */
    public function getNomComplet(): string
    {
        return $this->nomComplet;
    }

    /**
     * @param string $nomComplet
     */
    public function setNomComplet(string $nomComplet): void
    {
        $this->nomComplet = $nomComplet;
    }

    /**
     * @return string|null
     */
    public function __toString(): ?string
    {
        return $this->nomComplet;
    }
}

class DB
{
    /**
     * @var string
     */
    protected $serveur;

    /**
     * @var string
     */
    protected $utilisateur;

    /**
     * @var string
     */
    protected $motDePasse;

    /**
     * @var string
     */
    protected $baseDeDonnees;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    protected $identifiant;

    /**
     * DB constructor.
     * @param string $serveur
     * @param string $utilisateur
     * @param string $motDePasse
     * @param string $baseDeDonnees
     * @param string $table
     * @param string $identifiant
     */
    public function __construct(
        string $serveur,
        string $utilisateur,
        string $motDePasse,
        string $baseDeDonnees,
        string $table,
        string $identifiant
    )
    {
        $this->serveur = $serveur;
        $this->utilisateur = $utilisateur;
        $this->motDePasse = $motDePasse;
        $this->baseDeDonnees = $baseDeDonnees;
        $this->table = $table;
        $this->identifiant = $identifiant;
    }

    /**
     * @param $id
     * @return mixed|null
     * @throws Exception
     */
    public function find($id)
    {
        $sql = sprintf("SELECT * FROM %s WHERE %s = %s", $this->table, $this->identifiant, $id);
        $object = $this->convert(
            $this->query($sql)
        );

        return count($object) === 1
            ? $object[0]
            : null;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function findAll(): array
    {
        $sql = sprintf("SELECT * FROM %s", $this->table);

        return $this->convert(
            $this->query($sql)
        );
    }

    /**
     * @param int $offset
     * @param int $count
     * @return array
     * @throws Exception
     */
    public function findLimit(int $offset = 0, int $count = 30): array
    {
        $sql = sprintf("SELECT * FROM %s LIMIT %d,%d", $this->table, $offset, $count);

        return $this->convert(
            $this->query($sql)
        );
    }

    /**
     * @param string $sql
     * @return mysqli_result
     * @throws Exception
     */
    protected function query(string $sql): mysqli_result
    {
        $mysqli = new mysqli(
            $this->serveur,
            $this->utilisateur,
            $this->motDePasse,
            $this->baseDeDonnees
        );

        if ($mysqli->connect_errno) {
            throw new Exception("Impossible de se connecter");
        }

        $result = $mysqli->query($sql);

        if ($result === false) {
            throw new Exception("Impossible d'exécuter la requête");
        }

        $mysqli->close();

        return $result;
    }

    /**
     * @param mysqli_result $mysqli_result
     * @return array
     */
    protected function convert(mysqli_result $mysqli_result): array
    {
        return $mysqli_result->fetch_all(MYSQLI_ASSOC);
    }
}

class DBProduit extends DB
{
    const TABLE_NAME = 'product';
    const TABLE_ID = 'id';

    /**
     * DBProduit constructor.
     * @param string $serveur
     * @param string $utilisateur
     * @param string $motDePasse
     * @param string $baseDeDonnees
     */
    public function __construct(
        string $serveur,
        string $utilisateur,
        string $motDePasse,
        string $baseDeDonnees
    )
    {
        parent::__construct(
            $serveur,
            $utilisateur,
            $motDePasse,
            $baseDeDonnees,
            self::TABLE_NAME,
            self::TABLE_ID
        );
    }

    /**
     * @param mysqli_result $mysqli_result
     * @return array|Produit[]
     */
    protected function convert(mysqli_result $mysqli_result): array
    {
        $produits = [];
        while ($row = $mysqli_result->fetch_assoc()) {
            $produits[] = new Produit(
                (int) $row['id'],
                $row['label'],
                null,
                (float) $row['price']
            );
        }
        return $produits;
    }
}

class DBUtilisateur extends DB
{
    const TABLE_NAME = 'users';
    const TABLE_ID = 'username';

    /**
     * DBProduit constructor.
     * @param string $serveur
     * @param string $utilisateur
     * @param string $motDePasse
     * @param string $baseDeDonnees
     */
    public function __construct(
        string $serveur,
        string $utilisateur,
        string $motDePasse,
        string $baseDeDonnees
    )
    {
        parent::__construct(
            $serveur,
            $utilisateur,
            $motDePasse,
            $baseDeDonnees,
            self::TABLE_NAME,
            self::TABLE_ID
        );
    }

    /**
     * @param mysqli_result $mysqli_result
     * @return array|Utilisateur[]
     */
    protected function convert(mysqli_result $mysqli_result): array
    {
        $utilisateurs = [];
        while ($row = $mysqli_result->fetch_assoc()) {
            $utilisateurs[] = new Utilisateur(
                $row['username'],
                $row['password'],
                $row['fullname']
            );
        }
        return $utilisateurs;
    }
}










