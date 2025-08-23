## Commit 01

Change docker-compose for docker compose, because docker-compose is deprecated. 

## Commit 02


### 🔹 1. **Resources em todos os métodos → padroniza a resposta**

* Use `UserResource` (`php artisan make:resource UserResource`) in **all response**.
* Control exposed fiels on API (ex.: `id, name, email, created_at`), ofr **security** an **consistence**

Output Sample:

```json
{
  "data": {
    "id": 1,
    "name": "Dario",
    "email": "dariosouzadasilva@gmail.com",
    "created_at": "2025-08-22 08:00:00"
  }
}
```

---

### 🔹 2. **`index()` pagination**

* `User::paginate(15)` return **pages with 15 registers** and;
* **navigation links** and pagination **metadados** for best **performance** in greater data volumes and better and modern API use **Experience**.

Output response:

```json
{
  "data": [...],
  "links": {
    "first": "http://api.local/users?page=1",
    "last": "http://api.local/users?page=10",
    "prev": null,
    "next": "http://api.local/users?page=2"
  },
  "meta": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

---

### 🔹 3. **Hashed password**

* For security and Laravel goo practicies, apply `bcrypt($data['password'])` in password.

---

### 🔹 4. **Return 201 Created on `store()`**

* For **RESTful padrão HTTP** best practcies, when the resource is created succefull, the correct response is **201 Created**.

---

### 🔹 5. **Return 204 No Content on `destroy()`**

* For **RESTful padrão HTTP** best practcies, when the resource is destroyed, the correct response is **204 No Content** .

---

### 🔹 6. **`JsonResponse` typed for clarify**

* Added `: JsonResponse` in all methods that returns JSON.
* Isso deixa o código mais **explícito**, ajuda IDEs, ferramentas de análise estática e outros desenvolvedores.

---

### 🔹 7. **Swagger (`@OA`) adjusted for return like API:**
  * `200 OK` with `UserResource` or paginated list.
  * `201 Created` or `store`.
  * `204 No Content` or `destroy`.

---
