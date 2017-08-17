-- Table: nc_action

-- DROP TABLE nc_action;

CREATE TABLE nc_action
(
  action_id serial NOT NULL,
  "action_fncId" integer NOT NULL,
  "action_debDate" character varying,
  "action_finDate" character varying,
  action_description text,
  action_responsable character varying(255),
  action_etat character varying(100),
  action_type character varying(100),
  action_pilote integer,
  action_date_suivi character varying,
  action_comment character varying(254),
  CONSTRAINT nc_action_pkey PRIMARY KEY (action_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_action
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_action TO pgtantely;
GRANT ALL ON TABLE nc_action TO dg;
GRANT ALL ON TABLE nc_action TO public;


-- Table: nc_action_liste

-- DROP TABLE nc_action_liste;

CREATE TABLE nc_action_liste
(
  id serial NOT NULL,
  libelle text,
  type character varying(100),
  CONSTRAINT nc_action_liste_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_action_liste
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_action_liste TO pgtantely;
GRANT ALL ON TABLE nc_action_liste TO dg;


-- Table: nc_chq

-- DROP TABLE nc_chq;

CREATE TABLE nc_chq
(
  id_nc_chq serial NOT NULL,
  active_chq smallint DEFAULT 0,
  matricule integer,
  CONSTRAINT nc_chq_pkey PRIMARY KEY (id_nc_chq),
  CONSTRAINT nc_chq_matricule_key UNIQUE (matricule)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_chq
  OWNER TO postgres;
GRANT ALL ON TABLE nc_chq TO postgres;
GRANT ALL ON TABLE nc_chq TO public;
COMMENT ON TABLE nc_chq
  IS 'active_chq = 1 : Active
active_chq = 0 :Off';


-- Table: nc_classement

-- DROP TABLE nc_classement;

CREATE TABLE nc_classement
(
  classement_id serial NOT NULL,
  classement_libelle character varying(128) NOT NULL
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_classement
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_classement TO pgtantely;
GRANT ALL ON TABLE nc_classement TO public;


-- Table: nc_comm

-- DROP TABLE nc_comm;

CREATE TABLE nc_comm
(
  comm_libelle character varying(255) NOT NULL,
  flagaffiche integer DEFAULT 0,
  CONSTRAINT nc_comm_pkey PRIMARY KEY (comm_libelle)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_comm
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_comm TO pgtantely;
GRANT ALL ON TABLE nc_comm TO dg;
GRANT ALL ON TABLE nc_comm TO public;


-- Table: nc_fiche

-- DROP TABLE nc_fiche;

CREATE TABLE nc_fiche
(
  fnc_id serial NOT NULL,
  fnc_code character varying(100),
  fnc_ref character varying(100),
  fnc_cp integer,
  fnc_comm character varying(100),
  "fnc_creationDate" character varying,
  fnc_type character varying(100),
  fnc_motif text,
  fnc_exigence text,
  fnc_statut character varying(100),
  fnc_cause text,
  "fnc_reponseDate" character varying,
  "fnc_reponseRef" text,
  fnc_createur integer,
  fnc_validateur integer,
  "fnc_modifDate" character varying,
  "fnc_modif_Matricule" integer,
  fnc_valide boolean DEFAULT false,
  fnc_client character varying(100),
  fnc_version integer,
  fnc_imputation integer,
  fnc_typologie integer,
  "fnc_creationHour" time without time zone,
  "fnc_actionCStatut" character varying(255),
  "fnc_actionNCStatut" character varying(255),
  fnc_process text,
  fnc_classement text,
  fnc_module text,
  fnc_typo text,
  fnc_autre_cplmnt character varying(100),
  fnc_traitement character varying(255),
  fnc_id_grille_application integer DEFAULT 0,
  fnc_id_notation integer DEFAULT 0,
  fnc_gravite_id integer,
  fnc_frequence_id integer,
  fnc_freq_cat_id integer,
  fnc_grav_cat_id integer,
  fnc_bu integer,
  id_cc_sr_typo integer DEFAULT 0,
  CONSTRAINT nc_fiche_pkey PRIMARY KEY (fnc_id),
  CONSTRAINT nc_fiche_fnc_ref_key UNIQUE (fnc_ref)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_fiche
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_fiche TO pgtantely;
GRANT ALL ON TABLE nc_fiche TO dg;
GRANT ALL ON TABLE nc_fiche TO public;

-- Index: idx_fiche

-- DROP INDEX idx_fiche;

CREATE INDEX idx_fiche
  ON nc_fiche
  USING btree
  (fnc_code COLLATE pg_catalog."default", fnc_cp, fnc_comm COLLATE pg_catalog."default", "fnc_creationDate" COLLATE pg_catalog."default", fnc_statut COLLATE pg_catalog."default", fnc_createur);

-- Index: nc_fiche_fnc_code_idx

-- DROP INDEX nc_fiche_fnc_code_idx;

CREATE INDEX nc_fiche_fnc_code_idx
  ON nc_fiche
  USING btree
  (fnc_code COLLATE pg_catalog."default");

-- Index: nc_fiche_fnc_id_idx

-- DROP INDEX nc_fiche_fnc_id_idx;

CREATE INDEX nc_fiche_fnc_id_idx
  ON nc_fiche
  USING btree
  (fnc_id);

-- Index: nc_fiche_fnc_ref_idx

-- DROP INDEX nc_fiche_fnc_ref_idx;

CREATE INDEX nc_fiche_fnc_ref_idx
  ON nc_fiche
  USING btree
  (fnc_ref COLLATE pg_catalog."default");


  
  -- Table: nc_fnc_action

-- DROP TABLE nc_fnc_action;

CREATE TABLE nc_fnc_action
(
  id serial NOT NULL,
  action_liste_id integer NOT NULL,
  fnc_id character varying NOT NULL,
  fnc_info_id integer,
  CONSTRAINT nc_fnc_action_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_fnc_action
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_fnc_action TO pgtantely;
GRANT ALL ON TABLE nc_fnc_action TO dg;


-- Table: nc_fnc_infos

-- DROP TABLE nc_fnc_infos;

CREATE TABLE nc_fnc_infos
(
  id serial NOT NULL,
  date_debut character varying,
  date_fin character varying,
  responsable character varying,
  etat character varying,
  faille_identifiee text,
  impact text,
  generalisation text,
  date_suivi character varying,
  commentaire text,
  indice character varying,
  indic_efficacite character varying,
  obj_echeance character varying,
  tx_avacmnt double precision DEFAULT 0,
  valid_action integer DEFAULT 0,
  CONSTRAINT nc_fnc_infos_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_fnc_infos
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_fnc_infos TO pgtantely;
GRANT ALL ON TABLE nc_fnc_infos TO dg;
GRANT ALL ON TABLE nc_fnc_infos TO public;


-- Table: nc_frequence

-- DROP TABLE nc_frequence;

CREATE TABLE nc_frequence
(
  id_frequence serial NOT NULL,
  echelle_frequence integer,
  "catId_freq" integer,
  CONSTRAINT nc_frequence_pkey PRIMARY KEY (id_frequence)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_frequence
  OWNER TO postgres;
GRANT ALL ON TABLE nc_frequence TO postgres;
GRANT ALL ON TABLE nc_frequence TO public;
GRANT ALL ON TABLE nc_frequence TO dg;
GRANT ALL ON TABLE nc_frequence TO adminbd;


-- Table: nc_frequence_categorie

-- DROP TABLE nc_frequence_categorie;

CREATE TABLE nc_frequence_categorie
(
  id_categorie_freq serial NOT NULL,
  echelle_id_freq integer,
  libelle_frequence text,
  CONSTRAINT nc_frequence_categorie_pkey PRIMARY KEY (id_categorie_freq)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_frequence_categorie
  OWNER TO postgres;
GRANT ALL ON TABLE nc_frequence_categorie TO postgres;
GRANT ALL ON TABLE nc_frequence_categorie TO public;
GRANT ALL ON TABLE nc_frequence_categorie TO dg;
GRANT ALL ON TABLE nc_frequence_categorie TO adminbd;


-- Table: nc_gravite

-- DROP TABLE nc_gravite;

CREATE TABLE nc_gravite
(
  id_gravite serial NOT NULL,
  echelle_gravite integer,
  "catId_grav" integer,
  CONSTRAINT nc_gravite_pkey PRIMARY KEY (id_gravite)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_gravite
  OWNER TO postgres;
GRANT ALL ON TABLE nc_gravite TO postgres;
GRANT ALL ON TABLE nc_gravite TO public;
GRANT ALL ON TABLE nc_gravite TO dg;
GRANT ALL ON TABLE nc_gravite TO adminbd;


-- Table: nc_gravite_categorie

-- DROP TABLE nc_gravite_categorie;

CREATE TABLE nc_gravite_categorie
(
  id_categorie_grav serial NOT NULL,
  echelle_id_grav integer,
  libelle_gravite text,
  CONSTRAINT nc_gravite_categorie_pkey PRIMARY KEY (id_categorie_grav)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_gravite_categorie
  OWNER TO postgres;
GRANT ALL ON TABLE nc_gravite_categorie TO postgres;
GRANT ALL ON TABLE nc_gravite_categorie TO public;
GRANT ALL ON TABLE nc_gravite_categorie TO dg;
GRANT ALL ON TABLE nc_gravite_categorie TO adminbd;


-- Table: nc_imputation

-- DROP TABLE nc_imputation;

CREATE TABLE nc_imputation
(
  imputation_id serial NOT NULL,
  imputation_libelle character varying(255),
  imputation_actif character(1),
  CONSTRAINT nc_imputation_pkey PRIMARY KEY (imputation_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_imputation
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_imputation TO pgtantely;
GRANT ALL ON TABLE nc_imputation TO dg;
GRANT ALL ON TABLE nc_imputation TO public;


-- Table: nc_imputation_typologie

-- DROP TABLE nc_imputation_typologie;

CREATE TABLE nc_imputation_typologie
(
  "nc_idTypologie" integer NOT NULL,
  "nc_idImputation" integer NOT NULL
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_imputation_typologie
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_imputation_typologie TO pgtantely;
GRANT ALL ON TABLE nc_imputation_typologie TO dg;
GRANT ALL ON TABLE nc_imputation_typologie TO public;


-- Table: nc_module

-- DROP TABLE nc_module;

CREATE TABLE nc_module
(
  module_id serial NOT NULL,
  module_libelle character varying(256) NOT NULL
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_module
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_module TO pgtantely;
GRANT ALL ON TABLE nc_module TO public;


-- Table: nc_motif

-- DROP TABLE nc_motif;

CREATE TABLE nc_motif
(
  id serial NOT NULL,
  libelle text,
  type_motif character varying(100),
  fnc_id integer,
  CONSTRAINT nc_motif_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_motif
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_motif TO pgtantely;
GRANT ALL ON TABLE nc_motif TO dg;
GRANT ALL ON TABLE nc_motif TO public;


-- Table: nc_process

-- DROP TABLE nc_process;

CREATE TABLE nc_process
(
  process_id serial NOT NULL,
  process_libelle character varying(256) NOT NULL
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_process
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_process TO pgtantely;
GRANT ALL ON TABLE nc_process TO public;


-- Table: nc_process

-- DROP TABLE nc_process;

CREATE TABLE nc_process
(
  process_id serial NOT NULL,
  process_libelle character varying(256) NOT NULL
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nc_process
  OWNER TO pgtantely;
GRANT ALL ON TABLE nc_process TO pgtantely;
GRANT ALL ON TABLE nc_process TO public;

