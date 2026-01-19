--1) Liste détaillée des joueurs triée selon deux critères (libre choix des critères)

SELECT * FROM G04_Joueur ORDER BY joueur_nom ASC, joueur_prenom ASC;

--2) Liste des joueurs dont le nom contient "ov" ou "ss"

SELECT * FROM G04_Joueur WHERE joueur_nom ~* 'ov|ss';

--3) Liste des vainqueurs de tournois entre 2020 et 2022

SELECT * FROM G04_Joueur 
WHERE id_joueur IN (
                    SELECT edi_vainqueur 
                    FROM G04_Edition_Tournois 
                    WHERE edi_date BETWEEN '2020-01-01' AND '2022-12-31' AND format = 'Simple'
)
OR id_joueur IN (
    SELECT eq_joueur1 FROM G04_Equipe 
    WHERE id_equipe IN (
                        SELECT edi_vainqueur 
                        FROM G04_Edition_Tournois 
                        WHERE edi_date BETWEEN '2020-01-01' AND '2022-12-31' AND format = 'Double'
    )
)
OR id_joueur IN (
    SELECT eq_joueur2 FROM G04_Equipe 
    WHERE id_equipe IN (
                        SELECT edi_vainqueur 
                        FROM G04_Edition_Tournois 
                        WHERE edi_date BETWEEN '2020-01-01' AND '2022-12-31' AND format = 'Double'
    )
);

--4) Nombre de tournois

SELECT COUNT(*) AS nbTournois FROM G04_Tournois;

--5) Détails des Tournois ayant un russe en finale (= vainqueur ou finaliste)
-- nom edition, nom tournoi, surface, date edition tournoi,lieu, format, nom vainqueur, prenom vainqueur, nom finaliste, prenom finaliste

SELECT edt.id_edition AS identifiant_edition, 
       tournoi.tour_nom AS nom_tournoi, 
       tournoi.tour_surface AS surface, 
       edt.edi_date AS date_edition, 
       tournoi.tour_lieu AS lieu, 
       edt.format AS format,
       j1.joueur_nom AS nom_vainqueur, 
       j1.joueur_prenom AS prenom_vainqueur, 
       j2.joueur_nom AS nom_finaliste, 
       j2.joueur_prenom AS prenom_finaliste

FROM G04_Edition_Tournois edt
JOIN G04_Tournois tournoi ON edt.id_tournois = tournoi.id_tournois
JOIN G04_Joueur j1 ON edt.edi_vainqueur = j1.id_joueur
JOIN G04_Joueur j2 ON edt.edi_finaliste = j2.id_joueur
    WHERE j1.joueur_nationalite = 'Russie' OR j2.joueur_nationalite = 'Russie'
    ORDER BY edt.edi_date DESC;

--6) Liste détaillée des paires de doubles

SELECT E.id_equipe, 
       J1.id_joueur AS joueur1_id, J1.joueur_nom AS joueur1_nom, J1.joueur_prenom AS joueur1_prenom, J1.joueur_nationalite AS joueur1_nationalite,
       J2.id_joueur AS joueur2_id, J2.joueur_nom AS joueur2_nom, J2.joueur_prenom AS joueur2_prenom, J2.joueur_nationalite AS joueur2_nationalite
FROM G04_Equipe E
INNER JOIN G04_Joueur J1 ON E.eq_joueur1 = J1.id_joueur
INNER JOIN G04_Joueur J2 ON E.eq_joueur2 = J2.id_joueur;

--7) Palmarès du joueur de votre choix

SELECT edt.id_edition, edt.id_tournois,edt.edi_date, edt.format,tournois.tour_surface, tournois.tour_nom
FROM G04_Edition_Tournois edt
JOIN G04_Tournois tournois ON edt.id_tournois = tournois.id_tournois
LEFT JOIN G04_Joueur joueur ON edt.edi_vainqueur = joueur.id_joueur
LEFT JOIN G04_Equipe eq ON edt.edi_vainqueur = eq.id_equipe
WHERE (joueur.joueur_nom = 'Koolhof' AND joueur.joueur_prenom = 'Wesley' AND edt.format = 'Simple')  
    OR 
    (eq.id_equipe IN (SELECT id_equipe FROM G04_Equipe WHERE eq_joueur1 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Koolhof' AND joueur_prenom = 'Wesley')
        OR eq_joueur2 = (SELECT id_joueur FROM G04_Joueur WHERE joueur_nom = 'Koolhof' AND joueur_prenom = 'Wesley')
    ) AND edt.format = 'Double');


--8) Liste des vainqueurs d'une édition d'un tournoi de votre choix

SELECT joueur.id_joueur, joueur.joueur_nom AS vainqueur_nom, joueur.joueur_prenom AS vainqueur_prenom
FROM G04_Edition_Tournois edt
JOIN G04_Tournois tournoi ON edt.id_tournois = tournoi.id_tournois
LEFT JOIN G04_Joueur joueur ON edt.edi_vainqueur = joueur.id_joueur
LEFT JOIN G04_Equipe eq ON edt.edi_vainqueur = eq.id_equipe
WHERE tournoi.tour_nom = 'Rolex Paris Masters' AND edt.edi_vainqueur IS NOT NULL;


--9) Nombre de finales (remportées ou perdues) par joueur

SELECT joueur.id_joueur, joueur.joueur_nom, joueur.joueur_prenom, joueur.joueur_nationalite,
COUNT(*) AS nombre_finales
FROM G04_Joueur joueur
JOIN G04_Edition_Tournois edt ON joueur.id_joueur = edt.edi_vainqueur OR joueur.id_joueur = edt.edi_finaliste
GROUP BY joueur.id_joueur;

--10) Joueurs ayant remporté au moins 3 tournois

SELECT joueur.id_joueur, joueur.joueur_nom, joueur.joueur_prenom, joueur.joueur_nationalite, edt.format, 
COUNT(edt.id_edition) AS nombre_tournois_remporte
FROM G04_Joueur joueur
JOIN G04_Edition_Tournois edt ON joueur.id_joueur = edt.edi_vainqueur
GROUP BY joueur.id_joueur, edt.format
HAVING COUNT(edt.id_edition) >= 3;