┌────────────────────────────┐
│        BROWSER            │
│  (index.html + JS fetch)  │
└────────────┬──────────────┘
             │
             ▼
    Controlla localStorage:
    c'è un ETag salvato?
             │
      ┌──────┴──────┐
      │             │
      ▼             ▼
[NO: nessun ETag]  [SÌ: ETag esiste]
   (prima fetch)     (invia If-None-Match con ETag)
      │             │
      └──────┬──────┘
             ▼
     ┌────────────────┐
     │     SERVER     │
     │ (messaggio.php)│
     └──────┬─────────┘
            │
     Genera nuovo ETag
            │
            ▼
  Confronta ETag client con
  ETag corrente (da contenuto)
            │
     ┌──────┴──────┐
     │             │
     ▼             ▼
 [ETag DIVERSO]   [ETag UGUALE]
     │             │
HTTP 200 OK    HTTP 304 Not Modified
+ nuovo ETag   (nessun contenuto)
+ nuovo contenuto
     │             │
     ▼             ▼
Salva nuovo   Mostra contenuto
ETag nel      dalla cache (opzionale)
localStorage
     │             │
     └──────┬──────┘
            ▼
         Fine ciclo
