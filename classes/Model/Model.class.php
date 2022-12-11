<?php
include_once "../../classes/dbh.class.php";
class Model extends dbh{
    
    public function saveNew($choice,$payLoad){
        $sql = "insert into $choice (nom,prix,description,categorie,image) values(?,?,?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$payLoad['nom'],$payLoad['prix'],$payLoad['description'],$payLoad['categorie'],$payLoad['image']]);
    }

    public function delete($choice,$id){

            $sql = "delete from $choice where id_$choice=$id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            
    }
    public function listItems($choice){
        $sql = "select * from $choice";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        
       return $stmt->fetchAll();
            /* 0=>["id"=>1, "name"=>"club1"],
            1=>["id"=>2, "name"=>"club2"], */
    }

    public function getClub($id){
        $sql = "select * from club where id=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id]);
        $results = $stmt->fetch();
        return $results;
      /*  return $stmt; */
    }
    public function getClubName($id){
        $sql = "select nom from club where id=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
       
        return $result["nom"];
      /*  return $stmt; */
    }

    public function getClubMembersID($id_club){
        
        // this function returns associatuve array of club member_ids
        $sql = "select id_membre from membre where id_club=$id_club";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        /* $stmt->fetchall();  */
        /* $clubMembers = $stmt->fetchall();   */

        return $stmt;
    }
    public function getClubMembersRows($id_club){
        
        // this function returns associatuve array of club member_ids
        $sql = "select * from membre where id_club=$id_club";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchall();  
        /* $clubMembers = $stmt->fetchall();   */

        return $result;
    }
    public function getAllMembersRows(){
        
        // this function returns associatuve array of club member_ids
        $sql = "select * from membre";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchall();  
        /* $clubMembers = $stmt->fetchall();   */

        return $result;
    }
    public function getClubRepID($id_club){
        
        // this function returns associatuve array of club member_ids
        $sql = "select rep from club where id=$id_club";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        /* $stmt->fetchall();  */
        /* $clubMembers = $stmt->fetchall();   */
        
        $result=$stmt->fetch();
        return $result["rep"];
    }
    public function getClubRepName($id_club){
        $id_member = $this->getClubRepID($id_club);
        // this function returns associatuve array of club member_ids
        $sql = "select nom_complet from membre where id_membre=$id_member";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        /* $stmt->fetchall();  */
        /* $clubMembers = $stmt->fetchall();   */
        $result=$stmt->fetch();
        
        if (isset($result["nom_complet"])) {
            return $result["nom_complet"];
        }else return 'pas encors'; // put this back 
    }

    public function getClubMembersCount($id){
        
        $result= $this->getClubMembersID($id);
        $clubMembers= $result->rowCount();
    
        return $clubMembers;
    }
    

    public function updateClub($nom,$description,$id,$newrepID,$fileDestination){

        $club = $this->getClub($id);
        if (isset($club['rep'])) {
            $oldRep = $this->getClubRepName($id);
         
            $sql = "update membre SET membre_role='membre' WHERE nom_complet='$oldRep'";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
        /* }else {
            $repName = 'no rep';
        */} 
            

            $sql = "update club SET nom= '$nom',description='$description',rep='$newrepID',logo='$fileDestination' WHERE id=$id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();


            $sql = "update membre SET membre_role='rep' WHERE id_membre=$newrepID";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();

            
            /* return $id; */
        }
    
    public function saveReq($nom,$description,$id,$newrepID){
       
            $sql = "update club SET nom= '$nom',description='$description',rep=$newrepID WHERE id=$id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();

            
            
            return $id;
        }
    
    
    public function updateMembersCount($id){
       
        $club=$this->getClub($id);
        $newMembersCount = $club['membres']+1;
        
        $sql = "update club SET membres= '$newMembersCount' WHERE id=$id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        }
    
    
} 
