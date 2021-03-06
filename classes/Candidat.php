<?php

class Candidat {
    private $id;
    private $nom;
    private $prenom;
    private $telephone;
    private $email;
    private $linkedin;
    private $etape;
    private $disponibilites;
    private $mobilite;
    private $remuneration;
    
    public function __construct($id){
        $pdo = Database::connect();

        $statement = $pdo->prepare("SELECT * FROM candidats WHERE id_candidat = :id");
        $statement->bindParam('id', $id);
        $statement->execute();

        $infos = $statement->fetch();

        if($statement){
            $this->id = $infos['id_candidat'];
            $this->nom = $infos['nom'];
            $this->prenom = $infos['prenom'];
            $this->telephone = $infos['telephone'];
            $this->email = $infos['email'];
            $this->linkedin = $infos['linkedin'];
            $this->etape = $infos['etape'];
            $this->mobilite = $infos['mobilite'];
            $this->remuneration = $infos['remuneration'];
            $this->disponibilites = $infos['disponibilites'];
        } elseif(!$statement){
            
        }
        $pdo = null;

    }


    public static function add($infos) {
        $pdo = Database::connect();

        $add_candidate = $pdo->prepare("INSERT INTO candidats (nom, prenom) VALUES (:nom, :prenom)");
        $add_candidate->execute(array(':nom' => $infos['nom'], ':prenom' => $infos['prenom']));	
        echo "lol";
       $pdo =  null;
    }

    public function delete() {

        $pdo = Database::connect();

        $files = $this->get_files();

        foreach ($files as $key => $value) {
            unlink($_SERVER["DOCUMENT_ROOT"] . "/../files/" . $value["nom_serveur"]);
        }

        $delete_candidate = $pdo->prepare("DELETE FROM candidats WHERE id_candidat = :id");
        $delete_candidate->bindParam('id', $this->id);
        $delete_candidate->execute();

        $pdo = null;
    }

    public function send_modif(){
        
        $db = Database::connect();
        $statement = $db->prepare("UPDATE candidats SET disponibilites = :disponibilites, remuneration = :remuneration, mobilite = :mobilite, nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, linkedin = :linkedin WHERE id_candidat = :id");
        $statement->execute(array(
            ":nom" => $this->nom,
            ":prenom" => $this->prenom,
            ":email" => $this->email,
            ":telephone" => $this->telephone,
            ":linkedin" => $this->linkedin,
            ":id" => $this->id,
            ":disponibilites" => $this->disponibilites,
            ":remuneration" => $this->remuneration,
            ":mobilite" => $this->mobilite
        ));

    }

    public function transfer($id_pole){

        $data = Consultant::register($this->nom, $this->prenom, $id_pole);
        
        if(isset($data)) {
            $c = new Consultant($data['id']); 
            if(isset($c)) {
                $c->set_email($this->get_email());
                $c->set_telephone($this->get_telephone());
                $c->set_linkedin($this->get_linkedin());
                $c->send_modif();
                 
                $comp = $this->get_competences();
                $qualif = $this->get_qualifications();
                foreach($comp as $key => $value){

                    $c->add_competence($value);    
                    
                } 

                foreach($qualif as $key => $value){

                    $c->add_qualification($value);

                }
                
                $this->delete();
    
                return $data;
    
            }else{
                exit();
            }
        } else {
            exit();
        }


        

    }

    public function add_interview($infos) {
        $pdo = Database::connect();
        
        $statement = $pdo->prepare("INSERT INTO entretiens (id_candidat, id_rh, date_entretien, details) VALUES (:id_candidat, :id_rh, :date_entretien, :details)"); 


        $statement->execute(array(':id_candidat' => $this->id, ':id_rh' => $infos['id_rh'], ':date_entretien' => $infos['date_entretien'], ':details' => $infos['details']));
        
        var_dump( $infos);

        $pdo = null;

    }
    
    
    public function delete_interview($id){
        $pdo = Database::connect();

        $statement = $pdo->prepare("DELETE FROM entretiens WHERE id_entretien = :id");

        $statement->execute(array(':id' => $id));

        $pdo = null;


    }



    public function edit_interview($infos){
        $pdo = Database::connect();
        $first = true;

        $statement = "UPDATE entretiens SET ";
        if (isset($infos[':id_candidat'])) {
            if (!$first) {
                $statement .= ",";
                $first = false;
            }
            $statement .= " id_candidat = :id_candidat";
        }

        if (isset($infos[':id_rh'])) {
            if (!$first) {
                $statement .= ",";
                $first = false;
            }
            $statement .= " id_rh = :id_rh";
        }

        if (isset($infos[':date_entretien'])) {
            if (!$first) {
                $statement .= ",";
                $first = false;
            }
            $statement .= " date_entretien = :date_entretien";
        }

        if (isset($infos[':details'])) {
            if (!$first) {
                $statement .= ",";
                $first = false;
            }
            $statement .= " details = :details";
        }
        $statement .= " WHERE id_candidat = " . $this->id;
        $edit_interview = $pdo->prepare($statement);
        $edit_interview->execute($infos);		

        $pdo = null;


    }


    public function add_qualification($infos){
        $pdo = Database::connect();

        $statement = $pdo->prepare("INSERT INTO qualifications_candidats ( nom_qualification, id_candidat, date_obtention, details) VALUES (:nom_qualification, :id_candidat, :date_obtention, :details)");
        $statement->execute(array(':nom_qualification' => $infos['nom_qualification'], ':id_candidat' => $this->id, ':date_obtention' => $infos['date_obtention'], ':details' => $infos['details']));

    }

    public function delete_qualification($id){
        $pdo = Database::connect();

        $statement = $pdo->prepare("DELETE FROM qualifications_candidats WHERE id_qualification = :id_qualification AND id_candidat = :id_candidat");
        $statement->execute(array(':id_qualification' => $id, ':id_candidat' => $this->id));

    }

    public function add_competence($infos){ // deprecated

        $pdo = Database::connect();

        $statement = $pdo->prepare("INSERT INTO competences_candidats(id_competence, id_candidat, niveau) VALUES (:id_competence, :id_candidat, :niveau)");
        $statement->execute(array(':id_competence' => $infos['id_competence'], ':id_candidat' => $this->id, ':niveau' => $infos['niveau']));

    }

    public function edit_competence($infos){

        $pdo = Database::connect();

        $statement = $pdo->prepare("DELETE FROM `competences_candidats` WHERE `id_competence` = :id_competence AND `id_candidat` = :id_candidat");
        $statement->execute(array(
            ":id_candidat" => $this->id,
            ":id_competence" => $infos['id_competence']
        ));
        if ($infos["niveau"] == 0) return;
        $statement = $pdo->prepare("INSERT INTO competences_candidats (niveau, id_candidat, id_competence) VALUES (?, ?, ?)");
        $statement->execute(array(
           $infos['niveau'],
           $this->id,
           $infos['id_competence']
        ));
        
        $pdo = null; 
    
    }


    public function get_id(){
        return $this->id;
    }

    public function get_nom(){
        return $this->nom;
    }

    public function set_nom($nom) {
        $this->nom = $nom;
    }

    public function get_prenom(){
        return $this->prenom;
    }

    public function set_prenom($prenom){
        $this->prenom = $prenom;
    }

    public function get_email(){
        return $this->email;
    }

    public function set_email($email){
        $this->email = $email;
    }

    public function get_telephone(){
        return $this->telephone;
    }

    public function set_telephone($tel){
        $this->telephone = $tel;
    }

    public function get_linkedin(){
        return $this->linkedin;
    }

    public function set_linkedin($linkedin){
        $this->linkedin = $linkedin;
    }

    public function get_etape(){
        return $this->etape;
    }

    public function get_remuneration() {
        return $this->remuneration;
    }

    public function get_disponibilites() {
        return $this->disponibilites;
    }

    public function get_mobilite() {
        return $this->mobilite;
    }

    public function set_remuneration($rem) {
        $this->remuneration = $rem;
    }

    public function set_disponibilites($dispo) {
        $this->disponibilites = $dispo;
    }

    public function set_mobilite($mobi) {
        $this->mobilite = $mobi;
    }

    public function get_files($type = "") {

        $pdo = Database::connect();

        $statement = $pdo->prepare("SELECT * FROM fichiers_candidats WHERE id_candidat = :id AND type LIKE :type");
        $statement->execute(array(
            ":id" => $this->id,
            ":type" => "%" . $type . "%"
        ));

        $pdo = null;

        return $statement->fetchAll(PDO::FETCH_ASSOC);

    }

    public function add_file($trueName, $serverName, $type) {

        $pdo = Database::connect();
        
        $statement = $pdo->prepare("INSERT INTO fichiers_candidats VALUES (?, ?, ?, ?)");
        $statement->execute(array(
            $serverName,
            $this->id,
            $trueName,
            $type
        ));

        $pdo = null;

    }

    public function del_file($serverFileName) {

        $pdo = Database::connect();
        
        $statement = $pdo->prepare("DELETE FROM fichiers_candidats WHERE id_candidat = :id AND nom_serveur = :name");
        $statement->execute(array(
            ":id" => $this->id,
            ":name" => $serverFileName
        ));

        $pdo = null;

    }

    public function set_etape($etape){
        $this->etape = $etape;
        $db = Database::connect();
        $statement = $db->prepare("UPDATE candidats SET etape = :etape WHERE id_candidat = :id");
        $statement->execute(array(
            ":etape" => $this->etape,
            ":id" => $this->id
        ));
    }

    public function get_interviews(){
        
        $pdo = Database::connect();

        $statement = $pdo->prepare("SELECT i.*, (SELECT nom FROM RH rh WHERE rh.id_rh = i.id_rh) as nom, (SELECT prenom FROM RH rh WHERE rh.id_rh = i.id_rh) as prenom from entretiens i WHERE id_candidat = :id");
        $statement->execute(array(":id" => $this->id));

        $pdo = null;

        return $statement->fetchAll(PDO::FETCH_ASSOC);

    }

    
    public function get_qualifications(){ 

        $pdo = Database::connect(); 

        $statement = $pdo->prepare("SELECT * FROM qualifications_candidats WHERE id_candidat = :id"); 
        $statement->execute(array(":id" => $this->id)); 

        $pdo = null; 

        return $statement->fetchAll(PDO::FETCH_ASSOC);    


    } 

    static public function get_array() {
    
        $pdo = Database::connect();

        $statement = $pdo->prepare("SELECT * from candidats ORDER BY nom");
        $statement->execute();
        $array = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $array;


    }
    public function get_competences(){
        
        $pdo = Database::connect();

        $statement = $pdo->prepare("SELECT * FROM competences_candidats WHERE id_candidat = :id");
        $statement->execute(array(":id" => $this->id));

        $pdo = null;

        return $statement->fetchAll(PDO::FETCH_ASSOC);

    }

}
