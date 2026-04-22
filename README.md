# IncluDO — Backend API

Backend del progetto **IncluDO**, un chatbot AI per l'orientamento ai corsi artigianali tradizionali.  
Sviluppato con **Laravel** (PHP) e collegato a **OpenAI** tramite API.

---

##  Descrizione del progetto

IncluDO è un assistente virtuale di nome **Indo** che guida l'utente nella scelta di un corso artigianale.  
Il sistema raccoglie le preferenze dell'utente durante la conversazione e, nel momento giusto, cerca automaticamente i corsi più adatti nel database usando la **ricerca semantica** (RAG).

---

##  Tecnologie utilizzate

| Tecnologia | Utilizzo |
|------------|----------|
| **Laravel 11** (PHP) | Framework backend |
| **MySQL** | Database |
| **OpenAI API** | Chat (`gpt-4o-mini`) + Embeddings (`text-embedding-3-small`) |
| **Railway** | Hosting in produzione |

---

##  Come funziona

### 1. Conversazione con l'AI
Il frontend invia il messaggio dell'utente all'endpoint `/api/chat`.  
Il backend recupera tutta la cronologia della conversazione dal database e la invia a OpenAI, che risponde come "Indo".

### 2. Ricerca semantica (RAG)
Dopo 3–4 messaggi, OpenAI decide autonomamente di chiamare lo strumento `searchCourses`.  
Il backend allora:
1. Converte la query dell'utente in un **vettore numerico** (embedding) tramite OpenAI
2. Confronta quel vettore con i vettori di tutti i corsi nel database
3. Usa la **cosine similarity** per trovare i corsi più pertinenti
4. Restituisce i risultati all'AI, che li presenta all'utente in linguaggio naturale

### 3. Caricamento corsi
L'endpoint `/api/embed-course` permette di aggiungere un nuovo corso al database.  
Il backend lo converte automaticamente in vettore (embedding) e lo salva, pronto per la ricerca semantica.

---

##  Endpoint API

| Metodo | URL | Descrizione |
|--------|-----|-------------|
| `POST` | `/api/chat` | Invia un messaggio e riceve la risposta di Indo |
| `POST` | `/api/embed-course` | Aggiunge un corso al database con il suo embedding |

### Esempio — `/api/chat`
```json
// Request
{
  "session_id": "sessione-abc123",
  "message": "Mi piace lavorare con le mani"
}

// Response
{
  "reply": "Interessante! Hai già esperienza con qualche materiale specifico, come legno, ceramica o tessuto?"
}
```

### Esempio — `/api/embed-course`
```json
// Request
{
  "id": 1,
  "title": "Ceramica Tradizionale",
  "description": "Corso introduttivo alla lavorazione dell'argilla",
  "skills": ["modellazione", "cottura", "smaltatura"],
  "duration": "3 mesi",
  "remote": false
}

// Response
{
  "status": "success",
  "id": 1
}
```

---

##  Installazione locale

### Prerequisiti
- PHP >= 8.2
- Composer
- Una chiave API di OpenAI

### Passaggi

```bash
# 1. Clona il repository
git clone https://github.com/tuo-utente/includo-backend.git
cd includo-backend

# 2. Installa le dipendenze PHP
composer install

# 3. Crea il file di configurazione
cp .env.example .env

# 4. Genera la chiave dell'applicazione
php artisan key:generate

# 5. Crea il database e le tabelle
php artisan migrate

# 6. Avvia il server
php artisan serve
```

### Configurazione `.env`
Aggiungi la tua chiave OpenAI nel file `.env`:

```
OPENAI_API_KEY=sk-...la-tua-chiave...
```

---

##  Struttura del progetto

```
includo-backend/
├── app/
│   ├── Http/Controllers/
│   │   ├── ChatController.php      # Gestisce la chat con l'AI e il flusso RAG
│   │   └── CourseController.php    # Gestisce embedding e ricerca corsi
│   └── Models/
│       ├── Conversation.php        # Modello per la cronologia chat
│       └── Course.php              # Modello per i corsi artigianali
├── database/
│   └── migrations/                 # Struttura delle tabelle del database
├── routes/
│   └── api.php                     # Definizione degli endpoint API
└── .env.example                    # Variabili d'ambiente (template)
```

---

##  Deploy

Il backend è deployato su **Railway** con deploy automatico dal branch `main`.  
Il database utilizzato è **MySQL**, sia in locale che in produzione.

Il frontend (React) è deployato separatamente su **Vercel** e comunica con questo backend tramite la variabile d'ambiente `VITE_API_URL`.

---

## Autore

Nicolò

📧 Email: nicomelzi05@gmail.com

🌐 GitHub: https://github.com/nico25m

💼 LinkedIn: https://linkedin.com/in/nicolò-melzi

🌐 Link al Progetto: https://nico25m.github.io/includo-backend/

🌐 Link al Progetto webhosted: https://includo-frontend.vercel.app/

