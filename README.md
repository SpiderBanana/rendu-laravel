# Music-App Laravel Project

Un projet Laravel 12 + Jetstream/Inertia + Vue 3 qui implémente :

- Un CRUD de clés API (table `api_keys`) accessible depuis le dashboard (`/dashboard/api-keys`)  
- Un endpoint `GET /api/playlists` protégé par un header `x-api-key`  
- Un modèle `Playlist` associé à l’utilisateur (avec relations many-to-many vers `Track` si besoin)
