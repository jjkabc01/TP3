/**** TP3 *******/ 

SET SERVEROUTPUT ON

/***** Création des tables du cas CIPRÉ ******/

drop table TP2_MEMBRE cascade constraints;
drop table TP2_PROJET cascade constraints;
drop table TP2_EQUIPE_PROJET cascade constraints;
drop table TP2_NOTIFICATION cascade constraints;
drop table TP2_RAPPORT cascade constraints;
drop table TP2_RAPPORT_ETAT cascade constraints;
drop table TP2_INSCRIPTION_CONFERENCE cascade constraints;
drop table TP2_CONFERENCE cascade constraints;
drop sequence NO_MEMBRE_SEQ;
drop sequence NO_PROJET_SEQ;
drop sequence NO_NOTIFICATION_SEQ;
drop sequence NO_RAPPORT_SEQ;


create table TP2_MEMBRE (
  NO_MEMBRE number(10) not null, 
  UTILISATEUR_MEM varchar2(20) not null,
  MOT_DE_PASSE_MEM varchar2(20) not null,
  NOM_MEM varchar2(30) not null,
  PRENOM_MEM varchar2(30) not null,
  ADRESSE_MEM varchar2(40) not null,  
  CODE_POSTAL_MEM char(7) not null,  /* 7 char parceque le code postal doit contenir un espace ? */
  PAYS_MEM varchar2(30) not null, 
  TEL_MEM char(13) not null, 
  FAX_MEM char(13) null,  
  LANGUE_CORRESPONDANCE_MEM varchar2(30) default 'Français' not null,
  NOM_FICHIER_PHOTO_MEM varchar2(200) null, 
  ADRESSE_WEB_MEM varchar2(30) null,
  INSTITUTION_MEM varchar2(30) not null,
  COURRIEL_MEM varchar2(30) not null,
  NO_MEMBRE_PATRON number(10) not null, 
  EST_ADMINISTRATEUR_MEM number(1) default 0 not null, 
  EST_SUPERVISEUR_MEM number(1) default 0 not null, 
  EST_APPOUVEE_INSCRIPTION_MEM number(1) default 0 not null, 
  constraint CT_COURRIEL_MEM_FORMAT check (COURRIEL_MEM like '%_@__%.__%'),
  constraint CT_LONGUEUR_ADRESSE_MEM check ( length(ADRESSE_MEM) > 20 and length (ADRESSE_MEM) < 40),
  constraint CT_CODE_POSTAL_MEM 
	check ( regexp_like( CODE_POSTAL_MEM, '[a-zA-Z][0-9][a-zA-Z] [0-9][a-zA-Z][0-9]')),
  constraint AK_TP2_MEMBRE_COURRIEL_MEM unique (COURRIEL_MEM),
  constraint AK_TP2_MEMBRE_UTILISATEUR_MEM unique (UTILISATEUR_MEM),
  constraint AK_TP2_MEMBRE_PRENOM_MEM unique (PRENOM_MEM),
  constraint AK_TP2_MEMBRE_NOM_MEM unique (NOM_MEM),
  constraint AK_TP2_MEMBRE_ADRESSE_MEM unique (ADRESSE_MEM),
  constraint AK_TP2_MEMBRE_CODE_POSTAL_MEM unique (CODE_POSTAL_MEM),
  constraint PK_TP2_MEMBRE primary key (NO_MEMBRE),
  constraint FK_TP2_MEMBRE foreign key (NO_MEMBRE_PATRON) 
				references TP2_MEMBRE (NO_MEMBRE)	
 );


create table TP2_PROJET (
  NO_PROJET number(10) not null, 
  NOM_PRO varchar2(30) not null,
  MNT_ALLOUE_PRO number(9,2) default 0.0 not null, 
  STATUT_PRO varchar2(30) default 'Débuté' not null,
  DATE_DEBUT_PRO date not null,
  DATE_FIN_PRO date not null,
  constraint PK_TP2_PROJET primary key (NO_PROJET),
  constraint AK_TP2_PROJET_NOM_PRO unique (NOM_PRO),
  constraint CT_STATUT_PRO check (STATUT_PRO in ('Débuté', 'En vérification', 'En correction', 'Terminé')),
  constraint CT_MNT_ALLOUE_PRO_SUPERIEUR_EGAL_0 check(MNT_ALLOUE_PRO >= 0),
  constraint CT_DATE_FIN_PRO_SUPERIEUR_DATE_DEBUT_PRO check (DATE_FIN_PRO > DATE_DEBUT_PRO)
  
);

create table TP2_EQUIPE_PROJET (
  NO_MEMBRE number(10) not null, 
  NO_PROJET number(10) not null, 
  EST_DIRECTEUR_PRO number(1) default 0 not null, 
  constraint PK_TP2_EQUIPE_PROJET primary key (NO_MEMBRE, NO_PROJET),
  constraint FK_TP2_EQUIPE_PROJET_NO_MEMBRE foreign key (NO_MEMBRE) 
				references TP2_MEMBRE (NO_MEMBRE) ,
  constraint FK_TP2_EQUIPE_PROJET_NO_PROJET foreign key (NO_PROJET) 
				references TP2_PROJET (NO_PROJET) 

);


create table TP2_NOTIFICATION (
  NO_NOTIFICATION number(10) not null,
  NOM_NOT varchar2(30) not null,
  DATE_ECHEANCE_NOT date not null,
  ETAT_NOT varchar2(30) default 'Non débutée' not null,
  NOTE_NOT varchar2(1000) null,
  NO_MEM_ADMIN_CREATION number(10) not null,
  NO_MEM_ATTRIBUTION number(10) not null,
  constraint PK_TP2_NOTIFICATION primary key (NO_NOTIFICATION),
  constraint CT_ETAT_NOT check (ETAT_NOT in ('Non débutée', 'En cours', 'À approuver ', 'Terminée')),
  constraint FK_TP2_NOTIFICATION_NO_MEM_ADMIN_CREATION foreign key (NO_MEM_ADMIN_CREATION) 
				references TP2_MEMBRE (NO_MEMBRE),
  constraint FK_TP2_NOTIFICATION_NO_MEM_ATTRIBUTION foreign key (NO_MEM_ATTRIBUTION) 
				references  TP2_MEMBRE(NO_MEMBRE)
  
);


create table TP2_RAPPORT_ETAT (
  CODE_ETAT_RAP char(4) not null,
  NOM_ETAT_RAP varchar2(30) not null,
  constraint PK_TP2_RAPPORT_ETAT primary key (CODE_ETAT_RAP),
  constraint CT_CODE_ETAT_RAP check ( CODE_ETAT_RAP in ( 'DEBU', 'VERI', 'CORR', 'APPR')),
  constraint CT_NOM_ETAT_RAP check ( NOM_ETAT_RAP in ('Débuté', 'En vérification', 'En correction', 'Approuvé'))
   
);

create table TP2_RAPPORT (
  NO_RAPPORT number(10) not null,
  NO_PROJET number(10) not null,
  TITRE_RAP varchar2(30) not null,
  NOM_FICHIER_RAP varchar2(200) not null, 
  DATE_DEPOT_RAP date not null,
  CODE_ETAT_RAP char(4) not null,
  constraint PK_TP2_RAPPORT primary key (NO_RAPPORT),
  constraint AK_TP2_RAPPORT_NOM_FICHIER_RAP unique (NOM_FICHIER_RAP),
  constraint FK_TP2_RAPPORT_NO_PROJET foreign key (NO_PROJET) 
				references TP2_PROJET (NO_PROJET),
  constraint FK_TP2_RAPPORT_CODE_ETAT_RAP foreign key (CODE_ETAT_RAP) 
				references TP2_RAPPORT_ETAT (CODE_ETAT_RAP)
	
);


create table TP2_CONFERENCE (
  SIGLE_CONFERENCE varchar2(10) not null,
  TITRE_CON varchar2(40) not null,
  DATE_DEBUT_CON date not null,
  DATE_FIN_CON date not null,
  LIEU_CON varchar2(40) not null,
  ADRESSE_CON varchar2(40) not null,
  constraint PK_TP2_CONFERENCE primary key (SIGLE_CONFERENCE),
  constraint AK_TP2_CONFERENCE_TITRE_CON unique (TITRE_CON),
  constraint CT_LONGUEUR_ADRESSE_CON check ( length(ADRESSE_CON) > 20 and length (ADRESSE_CON) < 40)

);

create table TP2_INSCRIPTION_CONFERENCE (
  SIGLE_CONFERENCE varchar2(10) not null,
  NO_MEMBRE number(10) not null, 
  DATE_DEMANDE_INS date not null,
  STATUT_APPROBATION_INS number(1) default 0 not null,   /* default 'Non débutée' not null,     ( il faut trouver les valeurs possible de STATUT_APPROBATION_INS) */
  constraint PK_TP2_INSCRIPTION_CONFERENCE primary key (SIGLE_CONFERENCE, NO_MEMBRE),
  constraint FK_TP2_INSCRIPTION_CONFERENCE_SIGLE_CONFERENCE foreign key (SIGLE_CONFERENCE)
	references TP2_CONFERENCE (SIGLE_CONFERENCE),
  constraint FK_TP2_INSCRIPTION_CONFERENCE_NO_MEMBRE foreign key (NO_MEMBRE)
	references TP2_MEMBRE (NO_MEMBRE)
  /* ( il faut trouver les valeurs possible de STATUT_APPROBATION_INS)
	constraint CT_INSCRIPTION_CONFERENCE check (STATUT_APPROBATION_INS in ('Non débutée', 'En cours', 'À approuver', 'Terminée'))
  */
	
);



/***** Creation des séquences ****/

create sequence NO_MEMBRE_SEQ
    start with 5
    increment by 5;
    
create sequence NO_PROJET_SEQ
    start with 1000
    increment by 1;
    
create sequence NO_NOTIFICATION_SEQ
    start with 1000
    increment by 1;
    
create sequence NO_RAPPORT_SEQ
    start with 1000
    increment by 1;
    
    
    
    
    
    
/******* Creation des Vues initiales *****/
    
    create or replace view VUE_ADMINISTRATEUR ( UTILISATEUR_ADMINISTRATEUR, MOT_DE_PASSE_ADM, COURRIEL_ADM, TEL_ADM, NOM_ADM, PRENOM_ADM, NO_MEMBRE)
        as select UTILISATEUR_MEM, MOT_DE_PASSE_MEM, COURRIEL_MEM, TEL_MEM, NOM_MEM, PRENOM_MEM,NO_MEMBRE
            from TP2_MEMBRE
            where EST_ADMINISTRATEUR_MEM = 1;
    
    create or replace view VUE_SUPERVISEUR ( UTILISATEUR_ADMINISTRATEUR, MOT_DE_PASSE_ADM, COURRIEL_ADM, TEL_ADM, NOM_ADM, PRENOM_ADM, NO_MEMBRE)
        as select UTILISATEUR_MEM, MOT_DE_PASSE_MEM, COURRIEL_MEM, TEL_MEM, NOM_MEM, PRENOM_MEM,NO_MEMBRE
            from TP2_MEMBRE
            where EST_SUPERVISEUR_MEM = 1;
            
            

  /*********** Question 2d) Donnez la requête SQL qui crée une fonction nommée FCT_GENERER_MOT_DE_PASSE pour la génération des mot de passe des membres ***********/
  
 create or replace function FCT_GENERER_MOT_DE_PASSE(V_NB_CARACTERE in number) return varchar2
  is
    V_NB_CARACTERE_FINALE number := 0;
    V_MOT_DE_PASSE varchar2 (14);
    V_MOT_DE_PASSE_temp varchar2 (14);
    V_QUIT_LOOP number (1) := 0;
    
  begin 
  
    V_NB_CARACTERE_FINALE := V_NB_CARACTERE;
  
    if V_NB_CARACTERE < 7 then
        V_NB_CARACTERE_FINALE := 7;
    end if;
        
    if V_NB_CARACTERE > 14 then
        V_NB_CARACTERE_FINALE := 14;
    end if;
     
    while V_QUIT_LOOP = 0
        loop
            V_MOT_DE_PASSE := dbms_random.string('a', V_NB_CARACTERE_FINALE-4) || substr('!?&$/|#', dbms_random.value (1, 8), 1 ) || dbms_random.string('x', 1)  || dbms_random.string('l', 1) ||  TRUNC(DBMS_RANDOM.value(1,9));
            V_QUIT_LOOP := 1;

           if (regexp_count(V_MOT_DE_PASSE, '[a-z]') > 0
                and regexp_count(V_MOT_DE_PASSE, '[A-Z]') > 0 
                and regexp_count(V_MOT_DE_PASSE, '[0-9]') > 0
                or regexp_count(V_MOT_DE_PASSE, '[!|?|&|$|/|#|]') > 0) then
                    V_QUIT_LOOP := 1;
            end if;
          
            
        end loop;
        
    return (V_MOT_DE_PASSE);
    
  end FCT_GENERER_MOT_DE_PASSE;
  /
  
  select FCT_GENERER_MOT_DE_PASSE(7) from DUAL;
  
  


/*****1)m) requête SQL définissant la vue affichant la hiérarchie des membres **********/

/*****1)m)i) requête SQL qui crée cette vue. ******/

create or replace view VUE_HIERACHIE_MEMBRE as 
    with TOUS_MANAGER( NO_MEMBRE, NO_MEMBRE_PATRON, NOM_MEM, COURRIEL_MEM, TEL_MEM, UTILISATEUR_MEM, NIVEAU ) as 
    ( select  NO_MEMBRE, NO_MEMBRE_PATRON, NOM_MEM, COURRIEL_MEM, TEL_MEM, UTILISATEUR_MEM, 1 
        from TP2_MEMBRE 
        union all
    select ENFANT.NO_MEMBRE, ENFANT.NO_MEMBRE_PATRON, ENFANT.NOM_MEM, ENFANT.COURRIEL_MEM, ENFANT.TEL_MEM, ENFANT.UTILISATEUR_MEM as chemin, NIVEAU+1
        from TOUS_MANAGER PERE, TP2_MEMBRE ENFANT 
        where PERE.NO_MEMBRE = ENFANT.NO_MEMBRE_PATRON  and ENFANT.NO_MEMBRE_PATRON is null)
        
    select lpad(' ', (NIVEAU) * 2, ' ') || COURRIEL_MEM as ARBRE, NO_MEMBRE, NO_MEMBRE_PATRON, NOM_MEM, TEL_MEM, sys_connect_by_path(UTILISATEUR_MEM, '/') as CHEMIN,
    level as NIVEAU
    from TOUS_MANAGER
    connect by nocycle prior NO_MEMBRE = NO_MEMBRE_PATRON 
    start with NO_MEMBRE_PATRON is not null
       
       with check option;
      

    
    /*************************** 2)a) la requête SQL qui crée un déclencheur en ajout et modification sur la table EQUIPE_PROJET et qui s’assure que pour un projet, il y a au plus un membre qui est directeur.  ********************************************/
  
       create or replace trigger TRG_BIU_DIRECTEUR_PROJET
            before insert or update of EST_DIRECTEUR_PRO on TP2_EQUIPE_PROJET
            for each row 
            when (NEW.EST_DIRECTEUR_PRO = 1)
       declare
            V_NB_DIRECTEUR_PROJET number(1);
      begin            
            if :OLD.EST_DIRECTEUR_PRO  = 1 then
                raise_application_error(-20052, 'Ce projet à déjà un directeur');
            end if;
                              
            if :OLD.EST_DIRECTEUR_PRO is null  then 
                select count(*) into V_NB_DIRECTEUR_PROJET
                    from TP2_EQUIPE_PROJET
                where NO_PROJET = :NEW.NO_PROJET and EST_DIRECTEUR_PRO = 1 ;                
            else 
                dbms_output.put_line('OIF3- ' || :OLD.EST_DIRECTEUR_PRO || ' - ' || :NEW.EST_DIRECTEUR_PRO );
                select count(*) into V_NB_DIRECTEUR_PROJET
                    from TP2_EQUIPE_PROJET
                where NO_PROJET = :NEW.NO_PROJET and EST_DIRECTEUR_PRO = 1 ;
            end if;
            
            if V_NB_DIRECTEUR_PROJET > 0 then
                raise_application_error(-20052, 'Ce projet à déjà un directeur');
                end if;
    end TRG_BIU_DIRECTEUR_PROJET;
    /


   	
   	/******************* Question 2) b) Fonction FCT_MOYENNE_MNT_ALLOUE qui reçoit en paramètre un numéro de membre et retourne le montant moyen alloué pour tous ses projets **************/
   	
    create or replace function FCT_MOYENNE_MNT_ALLOUE(V_NO_MEMBRE in number) return number
    is 
        V_MNT_MOYEN TP2_PROJET.MNT_ALLOUE_PRO%type;
    begin
        select avg(P.MNT_ALLOUE_PRO) into V_MNT_MOYEN
            from TP2_PROJET P, TP2_EQUIPE_PROJET E
            where P.NO_PROJET = E.NO_PROJET and E.NO_MEMBRE = V_NO_MEMBRE
            group by E.NO_MEMBRE;
            return V_MNT_MOYEN;
        
    end FCT_MOYENNE_MNT_ALLOUE;
    /
    
    
    /******************** Question 2) c) procédure stockée SP_ARCHIVER_PROJET qui reçoit en paramètre une date et déplace tous les projets dans une nouvelle table PROJET_ARCHIVE et leurs rapports dans la table RAPPORT_ARCHIVE. **********/
    
    /******************** Creation des Tables PROJET_ARCHIVE et RAPPORT_ARCHIVE ******************/
    
    drop table TP2_PROJET_ARCHIVE cascade constraints;
    drop table TP2_RAPPORT_ARCHIVE cascade constraints;
    
    create table TP2_PROJET_ARCHIVE (
      NO_PROJET number(10) not null, 
      NOM_PRO varchar2(30) not null,
      MNT_ALLOUE_PRO number(9,2) default 0.0 not null, 
      STATUT_PRO varchar2(30) default 'Initiale' not null,
      DATE_DEBUT_PRO date not null,
      DATE_FIN_PRO date not null,
      constraint PK_TP2_PROJET_ARCHIVE primary key (NO_PROJET)
);
    
    create table TP2_RAPPORT_ARCHIVE (
      NO_RAPPORT number(10) not null,
      NO_PROJET number(10) not null,
      TITRE_RAP varchar2(30) not null,
      NOM_FICHIER_RAP varchar2(200) not null, 
      DATE_DEPOT_RAP date not null,
      CODE_ETAT_RAP char(4) not null,
      constraint PK_TP2_RAPPORT_ARCHIVE primary key (NO_RAPPORT),
      constraint FK_TP2_RAPPORT_ARCHIVE_NO_PROJET foreign key (NO_PROJET) 
                    references TP2_PROJET_ARCHIVE (NO_PROJET),
      constraint FK_TP2_RAPPORT_ARCHIVE_CODE_ETAT_RAP foreign key (CODE_ETAT_RAP) 
                    references TP2_RAPPORT_ETAT (CODE_ETAT_RAP)	
);

    /********* Création de la Procédure stockée TP3_SP_ARCHIVER_PROJET *************/
    create or replace procedure TP3_SP_ARCHIVER_PROJET (V_DATE_FIN_PROJET date, V_UTILISATEUR_ADMINISTRATEUR  varchar2) is 
        V_DATE_2_ANS date;
        V_EST_ADMINISTRATEUR number;
    begin 
        select (TRUNC(SYSDATE) - INTERVAL '2' YEAR) into V_DATE_2_ANS from DUAL ;   
        select EST_ADMINISTRATEUR_MEM into V_EST_ADMINISTRATEUR from TP2_MEMBRE where UTILISATEUR_MEM = V_UTILISATEUR_ADMINISTRATEUR;

 

        declare 
            E_DATE_INVALIDE exception;
            E_USAGER_INVALIDE exception;

 

            cursor ANCIEN_PROJET_CURSEUR is
                select NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO, DATE_FIN_PRO 
                    from TP2_PROJET
                    where DATE_FIN_PRO < V_DATE_FIN_PROJET  and STATUT_PRO = 'Terminé'
                    order by NO_PROJET asc;
                    
        begin
        
         if V_DATE_FIN_PROJET > V_DATE_2_ANS then
            raise E_DATE_INVALIDE;
         end if;
        
         if V_EST_ADMINISTRATEUR <> 1 then
            raise E_USAGER_INVALIDE;
         end if;
        
            for ENR_PROJET in ANCIEN_PROJET_CURSEUR
            loop 
                insert into TP2_PROJET_ARCHIVE( NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO, DATE_FIN_PRO) 
                    values ( ENR_PROJET.NO_PROJET, ENR_PROJET.NOM_PRO, ENR_PROJET.MNT_ALLOUE_PRO, ENR_PROJET.STATUT_PRO, ENR_PROJET.DATE_DEBUT_PRO, ENR_PROJET.DATE_FIN_PRO);
                    
                 insert into TP2_RAPPORT_ARCHIVE (NO_RAPPORT, NO_PROJET, TITRE_RAP, NOM_FICHIER_RAP, DATE_DEPOT_RAP, CODE_ETAT_RAP)
                    select NO_RAPPORT, NO_PROJET, TITRE_RAP, NOM_FICHIER_RAP, DATE_DEPOT_RAP, CODE_ETAT_RAP
                        from TP2_RAPPORT
                        where NO_PROJET = ENR_PROJET.NO_PROJET;
                
                
                delete from TP2_RAPPORT where NO_PROJET = ENR_PROJET.NO_PROJET;
                
                delete from TP2_PROJET where NO_PROJET = ENR_PROJET.NO_PROJET;
                
                
            end loop;         
                   
        exception
         When E_USAGER_INVALIDE then
                dbms_output.put_line('L''usager n''est pas administrateur');
         When E_DATE_INVALIDE then
                dbms_output.put_line('La date fournie dois être veille que 2 ans');
        end;   
   
end TP3_SP_ARCHIVER_PROJET;
  /

  
  
  /**************** 2)e) 3 requêtes PL/SQL de votre choix (1 stored procedure, 1 function et 1 trigger) *****************/
   /**************** 2)e)i) requêtes PL/SQL stored procedure pour la réinitialisation d' un mot de passe temporaire à un utilisateur *****************/
  create or replace procedure SP_RÉINITIALISER_MOT_DE_PASSE (V_NO_MEMBRE number, V_NB_CARACTERE number) is
  
    begin        
        update TP2_MEMBRE
            set MOT_DE_PASSE_MEM = FCT_GENERER_MOT_DE_PASSE(V_NB_CARACTERE)
            where NO_MEMBRE = V_NO_MEMBRE;
        
  end SP_RÉINITIALISER_MOT_DE_PASSE;
  /
   

   
   
   /**************** 2) e) ii) requêtes PL/SQL une fonction pour afficher la date d'une conference programmée *****************/
    
   insert into TP2_CONFERENCE ( SIGLE_CONFERENCE, TITRE_CON, DATE_DEBUT_CON, DATE_FIN_CON, LIEU_CON, ADRESSE_CON)
    values ('COOP', 'Sommet des coop', to_date('22-12-02','RR-MM-DD'), to_date('22-12-10','RR-MM-DD'), 'QUEBEC', '2325 RUE DELGADO QUEBEC CANADA');
    
    
   create or replace function FCT_DATE_CONFERENCE (P_I_SIGLE_CONFERENCE in varchar2) return date
    is
        V_DATE_DEBUT_CON date;
    begin
        select DATE_DEBUT_CON into V_DATE_DEBUT_CON from TP2_CONFERENCE
            where SIGLE_CONFERENCE = P_I_SIGLE_CONFERENCE;

        return V_DATE_DEBUT_CON;
        
    end FCT_DATE_CONFERENCE;
    /
    

   
   
   /********************************2) e) iii) requêtes PL/SQL un trigger qui empêche l'inscription à une conférence qui n'existe pas  *****************************************/
   
    create or replace trigger TRG_BI_INSCRIPTION_CONFERENCE
            before insert on TP2_INSCRIPTION_CONFERENCE
            for each row 
       declare
            V_EXISTE_CONFERENCE number(1);
      begin   
            select count(*) into V_EXISTE_CONFERENCE
                    from TP2_CONFERENCE
                where SIGLE_CONFERENCE = :NEW.SIGLE_CONFERENCE;    
                           
            if V_EXISTE_CONFERENCE < 1 then
                raise_application_error(-20053,  'La conférence n'' existe pas ');
            end if;
    end TRG_BIU_DIRECTEUR_PROJET;
    /

   
   
   /************************ 3) a)Indexation * ******************************************/
   /************************3) a) i)requêtes SQL dont vous auriez besoin pour créer les Index nécessaires pour améliorer les performances de ces recherches des membre  ******************************************/
     /* 
      drop index IDX_TP2_MEMBRE_INSTITUTION_MEM;
      drop index IDX_TP2_MEMBRE_INSTITUTION_NOM_MEM;
      drop index IDX_TP2_MEMBRE_NOM_INSTITUTION_MEM;
      drop index IDX_TP2_MEMBRE_NOM_PRENOM_MEM;
      drop index IDX_TP2_MEMBRE_PRENOM_NOM_MEM;
      */
  
            
      
        create index IDX_TP2_MEMBRE_INSTITUTION_MEM
            on TP2_MEMBRE (INSTITUTION_MEM);  
            
        create index IDX_TP2_MEMBRE_INSTITUTION_NOM_MEM
            on TP2_MEMBRE (INSTITUTION_MEM, NOM_MEM); 
            
        create index IDX_TP2_MEMBRE_NOM_INSTITUTION_MEM
            on TP2_MEMBRE (NOM_MEM, INSTITUTION_MEM); 
        
        create index IDX_TP2_MEMBRE_NOM_PRENOM_MEM
            on TP2_MEMBRE (NOM_MEM, PRENOM_MEM); 
            
        create index IDX_TP2_MEMBRE_PRENOM_NOM_MEM
            on TP2_MEMBRE (PRENOM_MEM, NOM_MEM);
            
            
        
        /****** Question 3)a)iii)2)Les requètes d'index pour chacune des trois situations *******/ 
        /******SITUATION 1 : Recherche d'une conférence *******/
        
        /*
        drop index IDX_TP2_CONFERENCE_TITRE_LIEU_CON;
        drop index IDX_TP2_CONFERENCE_LIEU_TITRE_CON;
        drop index IDX_TP2_CONFERENCE_TITRE_CON;
        drop index IDX_TP2_RAPPORT_NOM_FICHIER_DATE_DEPOT_RAP;
        drop index IDX_TP2_RAPPORT_DATE_DEPOT_NOM_FICHIER_RAP;
        drop index IDX_TP2_DATE_NOM_FICHIER_RAP;
        drop index IDX_TP2_PROJET_NOM_MONTANT_PRO;
        drop index IDX_TP2_PROJET_MONTANT_NOM_PRO;
      */
        
        
        create index IDX_TP2_CONFERENCE_TITRE_LIEU_CON
        on TP2_CONFERENCE(TITRE_CON, LIEU_CON);
        
        create index IDX_TP2_CONFERENCE_LIEU_TITRE_CON
        on TP2_CONFERENCE(LIEU_CON, TITRE_CON);
        
        
        /******SITUATION 2 : Recherche d'un rapport *******/
        
        create index IDX_TP2_RAPPORT_NOM_FICHIER_DATE_DEPOT_RAP
            on TP2_RAPPORT (NOM_FICHIER_RAP, DATE_DEPOT_RAP);
            
        create index IDX_TP2_RAPPORT_DATE_DEPOT_NOM_FICHIER_RAP
        on TP2_RAPPORT (DATE_DEPOT_RAP, NOM_FICHIER_RAP);
        
        
        /******SITUATION 3 : Recherche d'un projet *******/
        create index IDX_TP2_PROJET_NOM_MONTANT_PRO
        on TP2_PROJET (NOM_PRO, MNT_ALLOUE_PRO);
        
        create index IDX_TP2_PROJET_MONTANT_NOM_PRO
        on TP2_PROJET (MNT_ALLOUE_PRO, NOM_PRO);



/***** Données d'insertion *******/

/****************** PROJETS ******************/
insert into TP2_PROJET ( NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO, DATE_FIN_PRO ) 
    values (NO_PROJET_SEQ.nextval, 'projet synaps', 350000, 'Débuté', to_date('15-01-10','RR-MM-DD'), to_date('16-08-01','RR-MM-DD'));
    
insert into TP2_PROJET ( NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO, DATE_FIN_PRO ) 
    values (NO_PROJET_SEQ.nextval, 'projet epic', 620000, 'En correction', to_date('16-06-15','RR-MM-DD'), to_date('16-08-01','RR-MM-DD'));
    
insert into TP2_PROJET ( NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO, DATE_FIN_PRO ) 
    values (NO_PROJET_SEQ.nextval, 'projet cervo', 470000, 'Débuté', to_date('15-05-12','RR-MM-DD'), to_date('16-06-01','RR-MM-DD'));
    
insert into TP2_PROJET ( NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO, DATE_FIN_PRO ) 
    values (NO_PROJET_SEQ.nextval, 'projet intelijet', 320000, 'En correction', to_date('14-04-13','RR-MM-DD'), to_date('15-09-05','RR-MM-DD'));
    
insert into TP2_PROJET ( NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO, DATE_FIN_PRO ) 
    values (NO_PROJET_SEQ.nextval, 'projet mirage ', 470000, 'En vérification', to_date('13-03-12','RR-MM-DD'), to_date('14-05-06','RR-MM-DD'));
    
insert into TP2_PROJET ( NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO, DATE_FIN_PRO ) 
    values (NO_PROJET_SEQ.nextval, 'projet nuvera', 410000, 'Débuté', to_date('15-06-12','RR-MM-DD'), to_date('16-02-04','RR-MM-DD'));
    
insert into TP2_PROJET ( NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO, DATE_FIN_PRO ) 
    values (NO_PROJET_SEQ.nextval, 'projet fiery', 230000, 'En vérification', to_date('14-03-12','RR-MM-DD'), to_date('15-08-04','RR-MM-DD'));
    
    


/** Un administrateur ****/
insert into TP2_MEMBRE( NO_MEMBRE,  UTILISATEUR_MEM, MOT_DE_PASSE_MEM, NOM_MEM, PRENOM_MEM, ADRESSE_MEM, CODE_POSTAL_MEM, PAYS_MEM, TEL_MEM, FAX_MEM, LANGUE_CORRESPONDANCE_MEM,
  NOM_FICHIER_PHOTO_MEM, ADRESSE_WEB_MEM, INSTITUTION_MEM, COURRIEL_MEM, NO_MEMBRE_PATRON, EST_ADMINISTRATEUR_MEM, EST_SUPERVISEUR_MEM, EST_APPOUVEE_INSCRIPTION_MEM) 
    values ( NO_MEMBRE_SEQ.nextval, 'jean.tremblay', FCT_GENERER_MOT_DE_PASSE(14), 'tremblay', 'jean', '2325 Rue de la vie Etudiante', 'G1V 0B3', 'CANADA', '(514)699-3569','(514)699-4569','Français','/JTremblay.png','Jtremblay.com','NASA','jean.tremblay@nasa.com', 5 ,1,0,1);

/** Un superviseur **/
insert into TP2_MEMBRE( NO_MEMBRE,  UTILISATEUR_MEM, MOT_DE_PASSE_MEM, NOM_MEM, PRENOM_MEM, ADRESSE_MEM, CODE_POSTAL_MEM, PAYS_MEM, TEL_MEM, FAX_MEM, LANGUE_CORRESPONDANCE_MEM,
  NOM_FICHIER_PHOTO_MEM, ADRESSE_WEB_MEM, INSTITUTION_MEM, COURRIEL_MEM, NO_MEMBRE_PATRON, EST_ADMINISTRATEUR_MEM, EST_SUPERVISEUR_MEM, EST_APPOUVEE_INSCRIPTION_MEM) 
    values ( NO_MEMBRE_SEQ.nextval, 'eric.gagnon', FCT_GENERER_MOT_DE_PASSE(14), 'gagnon', 'eric', '2255 Rue des Pins Ouest', 'G1J 1T3', 'CANADA', '(418)646-2254','(418)646-2255','Français','/EGagnon.png','Egagnon.com','ETS','eric.gagnon@ets.com', 5 ,0,1,1);


/** un membre directeur **/
insert into TP2_MEMBRE( NO_MEMBRE,  UTILISATEUR_MEM, MOT_DE_PASSE_MEM, NOM_MEM, PRENOM_MEM, ADRESSE_MEM, CODE_POSTAL_MEM, PAYS_MEM, TEL_MEM, FAX_MEM, LANGUE_CORRESPONDANCE_MEM,
  NOM_FICHIER_PHOTO_MEM, ADRESSE_WEB_MEM, INSTITUTION_MEM, COURRIEL_MEM, NO_MEMBRE_PATRON, EST_ADMINISTRATEUR_MEM, EST_SUPERVISEUR_MEM, EST_APPOUVEE_INSCRIPTION_MEM) 
    values ( NO_MEMBRE_SEQ.nextval, 'julie.cagé', FCT_GENERER_MOT_DE_PASSE(14), 'cagé', 'julie', '1015 Avenue des Promenades', 'G1X 2P4', 'CANADA', '(418)353-1510','(418)353-1511','Français','/JCage.png','Jcage.com','PRISME','julie.cage@prisme.com', 15 ,0,0,1);


/** membres **/
insert into TP2_MEMBRE( NO_MEMBRE,  UTILISATEUR_MEM, MOT_DE_PASSE_MEM, NOM_MEM, PRENOM_MEM, ADRESSE_MEM, CODE_POSTAL_MEM, PAYS_MEM, TEL_MEM, FAX_MEM, LANGUE_CORRESPONDANCE_MEM,
  NOM_FICHIER_PHOTO_MEM, ADRESSE_WEB_MEM, INSTITUTION_MEM, COURRIEL_MEM, NO_MEMBRE_PATRON, EST_ADMINISTRATEUR_MEM, EST_SUPERVISEUR_MEM, EST_APPOUVEE_INSCRIPTION_MEM) 
    values ( NO_MEMBRE_SEQ.nextval, 'louis.lambert', FCT_GENERER_MOT_DE_PASSE(14), 'lambert', 'louis', '3686 Avenue de la liberation', 'G1V 3P4', 'CANADA', '(418)263-4410','(418)263-4412','Français','/LLambert.png','Llambert.com','ICON','louis.lambert@icon.com', 20 ,0,0,1);

insert into TP2_MEMBRE( NO_MEMBRE,  UTILISATEUR_MEM, MOT_DE_PASSE_MEM, NOM_MEM, PRENOM_MEM, ADRESSE_MEM, CODE_POSTAL_MEM, PAYS_MEM, TEL_MEM, FAX_MEM, LANGUE_CORRESPONDANCE_MEM,
  NOM_FICHIER_PHOTO_MEM, ADRESSE_WEB_MEM, INSTITUTION_MEM, COURRIEL_MEM, NO_MEMBRE_PATRON, EST_ADMINISTRATEUR_MEM, EST_SUPERVISEUR_MEM, EST_APPOUVEE_INSCRIPTION_MEM) 
    values ( NO_MEMBRE_SEQ.nextval, 'frederic.larouche', FCT_GENERER_MOT_DE_PASSE(14), 'larouche', 'frederic', '1516 Avenue de Sherbrooke', 'G1J 6V4', 'CANADA', '(418)552-4540','(418)552-4541','Français','/Flarouche.png','Flarouche.com','ICON','frederic.larouche@icon.com', 20 ,0,0,1);

insert into TP2_MEMBRE( NO_MEMBRE,  UTILISATEUR_MEM, MOT_DE_PASSE_MEM, NOM_MEM, PRENOM_MEM, ADRESSE_MEM, CODE_POSTAL_MEM, PAYS_MEM, TEL_MEM, FAX_MEM, LANGUE_CORRESPONDANCE_MEM,
  NOM_FICHIER_PHOTO_MEM, ADRESSE_WEB_MEM, INSTITUTION_MEM, COURRIEL_MEM, NO_MEMBRE_PATRON, EST_ADMINISTRATEUR_MEM, EST_SUPERVISEUR_MEM, EST_APPOUVEE_INSCRIPTION_MEM) 
    values ( NO_MEMBRE_SEQ.nextval, 'Sebastien.Plante', FCT_GENERER_MOT_DE_PASSE(14), 'Plante', 'Sebastien', '1766 Avenue du chateau', 'G1V 4P5', 'CANADA', '(418)334-4220','(418)334-4221','Français','/SPlante.png','Splante.com','PIXEL','sebastien.plante@icon.com', 20 ,0,0,1);


/************* EQUIPES PROJET SYNAPS *******/
 insert into TP2_EQUIPE_PROJET ( NO_MEMBRE, NO_PROJET, EST_DIRECTEUR_PRO) values (15, 1000, 1);
 insert into TP2_EQUIPE_PROJET ( NO_MEMBRE, NO_PROJET, EST_DIRECTEUR_PRO) values (20, 1000, 0);
 insert into TP2_EQUIPE_PROJET ( NO_MEMBRE, NO_PROJET, EST_DIRECTEUR_PRO) values (25, 1000, 0);
    
    
/***** EQUIPE PROJET EPIC ****/
 insert into TP2_EQUIPE_PROJET ( NO_MEMBRE, NO_PROJET, EST_DIRECTEUR_PRO) values (15, 1001, 1);
 insert into TP2_EQUIPE_PROJET ( NO_MEMBRE, NO_PROJET, EST_DIRECTEUR_PRO) values (20, 1001, 0);
 insert into TP2_EQUIPE_PROJET ( NO_MEMBRE, NO_PROJET, EST_DIRECTEUR_PRO) values (25, 1001, 0);
 
 
/***** EQUIPE PROJET CERVO ****/
insert into TP2_EQUIPE_PROJET ( NO_MEMBRE, NO_PROJET, EST_DIRECTEUR_PRO) values (15, 1002, 1);
insert into TP2_EQUIPE_PROJET ( NO_MEMBRE, NO_PROJET, EST_DIRECTEUR_PRO) values (20, 1002, 0);
insert into TP2_EQUIPE_PROJET ( NO_MEMBRE, NO_PROJET, EST_DIRECTEUR_PRO) values (30, 1002, 0);




insert into TP2_RAPPORT_ETAT ( CODE_ETAT_RAP, NOM_ETAT_RAP) values ( 'DEBU', 'Débuté');
  
insert into TP2_RAPPORT_ETAT ( CODE_ETAT_RAP, NOM_ETAT_RAP) values ( 'VERI', 'En vérification');
  
insert into TP2_RAPPORT_ETAT ( CODE_ETAT_RAP, NOM_ETAT_RAP) values ( 'CORR', 'En correction');
  
insert into TP2_RAPPORT_ETAT ( CODE_ETAT_RAP, NOM_ETAT_RAP) values ( 'APPR', 'Approuvé');
  
/**
select * from TP2_RAPPORT_ETAT;
**/

/*** RAPPORTS **/
insert into TP2_RAPPORT ( NO_RAPPORT, NO_PROJET, TITRE_RAP, NOM_FICHIER_RAP, DATE_DEPOT_RAP, CODE_ETAT_RAP)
    values ( NO_RAPPORT_SEQ.nextval, 1000, 'Rapport synaps', '/fichier_synaps.docx', to_date('16-06-01','RR-MM-DD'), 'DEBU');
    
insert into TP2_RAPPORT ( NO_RAPPORT, NO_PROJET, TITRE_RAP, NOM_FICHIER_RAP, DATE_DEPOT_RAP, CODE_ETAT_RAP)
    values ( NO_RAPPORT_SEQ.nextval, 1001, 'Rapport epic', '/fichier_epic.docx', to_date('16-07-01','RR-MM-DD'), 'CORR');
    
insert into TP2_RAPPORT ( NO_RAPPORT, NO_PROJET, TITRE_RAP, NOM_FICHIER_RAP, DATE_DEPOT_RAP, CODE_ETAT_RAP)
    values ( NO_RAPPORT_SEQ.nextval, 1002, 'Rapport cervo', '/fichier_cervo.docx', to_date('16-08-01','RR-MM-DD'), 'DEBU');
    
insert into TP2_RAPPORT ( NO_RAPPORT, NO_PROJET, TITRE_RAP, NOM_FICHIER_RAP, DATE_DEPOT_RAP, CODE_ETAT_RAP)
    values ( NO_RAPPORT_SEQ.nextval, 1003, 'RAPPORT Intelijet', '/fichier_Intelijet.docx', to_date('15-07-05','RR-MM-DD'), 'CORR');
    
insert into TP2_RAPPORT ( NO_RAPPORT, NO_PROJET, TITRE_RAP, NOM_FICHIER_RAP, DATE_DEPOT_RAP, CODE_ETAT_RAP)
    values ( NO_RAPPORT_SEQ.nextval, 1004, 'Rapport mirage', '/fichier_mirage.docx', to_date('14-01-06','RR-MM-DD'), 'VERI');
    
insert into TP2_RAPPORT ( NO_RAPPORT, NO_PROJET, TITRE_RAP, NOM_FICHIER_RAP, DATE_DEPOT_RAP, CODE_ETAT_RAP)
    values ( NO_RAPPORT_SEQ.nextval, 1005, 'Rapport nuvera', '/fichier_nuvera.docx', to_date('15-07-12','RR-MM-DD'), 'DEBU');
    
insert into TP2_RAPPORT ( NO_RAPPORT, NO_PROJET, TITRE_RAP, NOM_FICHIER_RAP, DATE_DEPOT_RAP, CODE_ETAT_RAP)
    values ( NO_RAPPORT_SEQ.nextval, 1006, 'Rapport fiery', '/fichier_fiery.docx', to_date('15-07-04','RR-MM-DD'), 'VERI');
    