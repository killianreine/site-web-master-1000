DROP TABLE IF EXISTS G04_Edition_Tournois, G04_Equipe, G04_Tournois, G04_Joueur CASCADE;

-- Création des tables

CREATE TABLE G04_Joueur (
    id_joueur SERIAL PRIMARY KEY,
    joueur_nom VARCHAR(50) NOT NULL,
    joueur_prenom VARCHAR(50) NOT NULL,
    joueur_nationalite VARCHAR(50) NOT NULL
);


CREATE TABLE G04_Tournois (
    id_tournois SERIAL PRIMARY KEY, 
    tour_nom VARCHAR(50) NOT NULL,
    tour_surface VARCHAR(8) NOT NULL, 
    CHECK (tour_surface IN ('Moquette', 'Dur')), 
    tour_lieu VARCHAR(50) NOT NULL
);


CREATE TABLE G04_Edition_Tournois (
    id_edition SERIAL PRIMARY KEY,
    id_tournois INTEGER,
    FOREIGN KEY (id_tournois) REFERENCES G04_Tournois(id_tournois) ON DELETE CASCADE,
    edi_date DATE NOT NULL,
    format VARCHAR(6) NOT NULL,
    CHECK (format IN ('Simple', 'Double')),
    edi_vainqueur INTEGER NOT NULL,
    edi_finaliste INTEGER NOT NULL
);


CREATE TABLE G04_Equipe (
    id_equipe SERIAL PRIMARY KEY,
    eq_joueur1 INTEGER NOT NULL,
    eq_joueur2 INTEGER NOT NULL,
    FOREIGN KEY (eq_joueur1) REFERENCES G04_Joueur(id_joueur) ON DELETE CASCADE,
    FOREIGN KEY (eq_joueur2) REFERENCES G04_Joueur(id_joueur) ON DELETE CASCADE
);

-- Insertion des valeurs

INSERT INTO G04_Joueur (joueur_nom, joueur_prenom, joueur_nationalite)
    VALUES  ('Humbert', 'Ugo', 'France'),
            ('Zverev', 'Alexander', 'Allemagne'), 
            ('Dimitrov', 'Grigor', 'Bulgarie'),
            ('Djokovic', 'Novak', 'Serbie'),
            ('Rune', 'Holger', 'Danemark'),
            ('Medvedev', 'Daniil', 'Russie'),
            ('Shapovalov', 'Denis', 'Canada'),
            ('Khachanov', 'Karen', 'Russie'),
            ('Krajinović', 'Filip', 'Serbie'),
            ('Sock', 'Jack', 'USA'), 
            ('Glasspool', 'Lloyd', 'Royaume-Unis'), 
            ('Pavlásek', 'Adam', 'République Tchèque'), 
            ('Koolhof', 'Wesley', 'Pays-Bas'), 
            ('Mektić', 'Nikola', 'Croatie'),
            ('Bopanna', 'Rohan', 'Inde'),
            ('Ebden', 'Matthew', 'Australie'), 
            ('González', 'Santiago', 'Mexique'), 
            ('Roger-Vasselin', 'Edouard', 'France'), 
            ('Dodig', 'Ivan', 'Croatie'), 
            ('Krajicek', 'Austin', 'USA'), 
            ('Skupski', 'Neal', 'Royaume-Unis'); 


INSERT INTO G04_Tournois (tour_nom, tour_surface, tour_lieu)
    VALUES  ('Rolex Paris Masters', 'Dur', 'Paris'),
            ('Open de Paris-Bercy', 'Moquette', 'Paris');


INSERT INTO G04_Equipe (eq_joueur1, eq_joueur2)
    VALUES  ((SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Glasspool' AND joueur_prenom = 'Lloyd'),
             (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Pavlásek' AND joueur_prenom = 'Adam')),

            ((SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Koolhof' AND joueur_prenom = 'Wesley'),
             (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Mektić' AND joueur_prenom = 'Nikola')),

            ((SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Bopanna' AND joueur_prenom = 'Rohan'),
             (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Ebden' AND joueur_prenom = 'Matthew')),

            ((SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'González' AND joueur_prenom = 'Santiago'),
             (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Roger-Vasselin' AND joueur_prenom = 'Edouard')),

            ((SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Dodig' AND joueur_prenom = 'Ivan'),
             (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Krajicek' AND joueur_prenom = 'Austin')),

            ((SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Skupski' AND joueur_prenom = 'Neal'),
             (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Koolhof' AND joueur_prenom = 'Wesley'));

INSERT INTO G04_Edition_Tournois (id_tournois, edi_date, format, edi_vainqueur, edi_finaliste)
    VALUES  ((SELECT id_tournois FROM G04_Tournois WHERE tour_nom = 'Rolex Paris Masters'), '2024-10-28', 'Simple', (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Zverev' AND joueur_prenom = 'Alexander'),
                                                                                                                    (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Humbert' AND joueur_prenom = 'Ugo')),

            ((SELECT id_tournois FROM G04_Tournois WHERE tour_nom = 'Rolex Paris Masters'), '2023-10-30', 'Simple', (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Djokovic' AND joueur_prenom = 'Novak'),
                                                                                                                    (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Dimitrov' AND joueur_prenom = 'Grigor')),
            
            ((SELECT id_tournois FROM G04_Tournois WHERE tour_nom = 'Rolex Paris Masters'), '2022-10-31', 'Simple', (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Rune' AND joueur_prenom = 'Holger'),
                                                                                                                    (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Djokovic' AND joueur_prenom = 'Novak')),
            
            ((SELECT id_tournois FROM G04_Tournois WHERE tour_nom = 'Rolex Paris Masters'), '2021-11-01', 'Simple', (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Djokovic' AND joueur_prenom = 'Novak'),
                                                                                                                    (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Medvedev' AND joueur_prenom = 'Daniil')),
            
            ((SELECT id_tournois FROM G04_Tournois WHERE tour_nom = 'Rolex Paris Masters'), '2020-11-02', 'Simple', (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Medvedev' AND joueur_prenom = 'Daniil'),
                                                                                                                    (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Zverev' AND joueur_prenom = 'Alexander')),

            ((SELECT id_tournois FROM G04_Tournois WHERE tour_nom = 'Rolex Paris Masters'), '2019-10-28', 'Simple', (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Djokovic' AND joueur_prenom = 'Novak'),
                                                                                                                    (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Shapovalov' AND joueur_prenom = 'Denis')),
            
            ((SELECT id_tournois FROM G04_Tournois WHERE tour_nom = 'Rolex Paris Masters'), '2018-10-29', 'Simple', (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Khachanov' AND joueur_prenom = 'Karen'),
                                                                                                                    (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Djokovic' AND joueur_prenom = 'Novak')),
            
            ((SELECT id_tournois FROM G04_Tournois WHERE tour_nom = 'Rolex Paris Masters'), '2017-10-30', 'Simple', (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Sock' AND joueur_prenom = 'Jack'),
                                                                                                                    (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Krajinović' AND joueur_prenom = 'Filip')),
            
            ((SELECT id_tournois FROM G04_Tournois WHERE tour_nom = 'Rolex Paris Masters'), '2024-10-28', 'Double', 
            (SELECT id_equipe FROM G04_Equipe 
                WHERE (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Koolhof' AND joueur_prenom = 'Wesley') 
                        OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Koolhof' AND joueur_prenom = 'Wesley'))
                
                AND (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Mektić' AND joueur_prenom = 'Nikola') 
                OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Mektić' AND joueur_prenom = 'Nikola'))
            ),

            (SELECT id_equipe FROM G04_Equipe 
                WHERE (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Glasspool' AND joueur_prenom = 'Lloyd') 
                    OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Glasspool' AND joueur_prenom = 'Lloyd'))
                
                AND (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Pavlásek' AND joueur_prenom = 'Adam') 
                OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Pavlásek' AND joueur_prenom = 'Adam'))
            )
        ),
        
        ((SELECT id_tournois FROM G04_Tournois WHERE tour_nom = 'Rolex Paris Masters'), '2023-10-30', 'Double', 
            (SELECT id_equipe FROM G04_Equipe 
                WHERE (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'González' AND joueur_prenom = 'Santiago') 
                OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'González' AND joueur_prenom = 'Santiago'))
                AND (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Roger-Vasselin' AND joueur_prenom = 'Edouard') 
                OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Roger-Vasselin' AND joueur_prenom = 'Edouard'))
            ),

            (SELECT id_equipe FROM G04_Equipe 
                WHERE (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Bopanna' AND joueur_prenom = 'Rohan') 
                    OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Bopanna' AND joueur_prenom = 'Rohan'))
                
                AND (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Ebden' AND joueur_prenom = 'Matthew') 
                OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Ebden' AND joueur_prenom = 'Matthew'))
            )
        ),
        
        ((SELECT id_tournois FROM G04_Tournois WHERE tour_nom = 'Rolex Paris Masters'), '2022-10-31', 'Double', 
            (SELECT id_equipe FROM G04_Equipe 
                WHERE (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Koolhof' AND joueur_prenom = 'Wesley') 
                    OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Koolhof' AND joueur_prenom = 'Wesley'))
                
                AND (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Skupski' AND joueur_prenom = 'Neal') 
                OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Skupski' AND joueur_prenom = 'Neal'))
            ),

            (SELECT id_equipe FROM G04_Equipe 
                WHERE (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Dodig' AND joueur_prenom = 'Ivan') 
                    OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Dodig' AND joueur_prenom = 'Ivan'))
                
                AND (eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Krajicek' AND joueur_prenom = 'Austin') 
                OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Krajicek' AND joueur_prenom = 'Austin'))
            )
        );