# Tests pour ChillFlix

## Tests unitaires

1. **Vérifier le format de la date d’un film**  
   Tester que la fonction qui formate la date d’un film affiche bien une date correcte.

2. **Calculer l’âge d’un utilisateur**  
   S’assurer qu’à partir de la date de naissance, la fonction renvoie le bon âge.

3. **Valider l’email lors de l’inscription**  
   Vérifier que la fonction refuse les adresses email mal formées.

4. **Récupérer la clé API OMDB**  
   Tester que la clé API est bien chargée depuis la configuration (fichier `.env` ou autre).

5. **Vérifier le format de l’ID IMDB**  
   Confirmer que les identifiants IMDB utilisés respectent le format attendu.

6. **Nettoyer la recherche utilisateur**  
   Tester que la fonction qui nettoie la chaîne de recherche supprime bien les caractères interdits ou dangereux.

---

## Tests d’intégration

1. **Ajouter un film aux favoris**  
   Vérifier que le film est bien ajouté dans la base de données via l’API.

2. **Retirer un film des favoris**  
   S’assurer que la suppression fonctionne bien et que la base de données est mise à jour.

3. **Récupérer la liste des favoris d’un utilisateur**  
   Confirmer que l’API renvoie bien la liste des films favoris enregistrés.

4. **Appeler OMDB et stocker les données**  
   Tester l’appel à l’API OMDB pour récupérer les infos d’un film et l’insertion dans la base locale.

---

## Tests fonctionnels

1. **S’inscrire avec un formulaire valide**  
   Remplir le formulaire d’inscription avec des données correctes, soumettre, et vérifier que le compte est créé.

2. **Se connecter avec un utilisateur existant**  
   Tester la connexion avec un compte valide et vérifier que l’utilisateur est bien redirigé.

3. **Ajouter un favori depuis l’interface**  
   Cliquer sur le bouton "Ajouter aux favoris" dans la fenêtre détails et voir si le bouton change d’état correctement.

4. **Naviguer entre les pages principales**  
   Vérifier que la navigation entre l’accueil, les favoris, l’inscription et la connexion fonctionne sans erreurs ni blocages.
