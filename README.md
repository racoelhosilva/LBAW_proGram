# ProGram - LBAW2411

## ProGram

ProGram is a social networking platform being developed by a startup which aims to connect programmers with like-minded individuals. The need for a specialized space where technology professionals can connect, collaborate, and share knowledge drives this initiative. By providing an interactive and supportive environment, ProGram seeks to fill this gap within the tech community.

## Installation

In order to run the project, it is necessary to login into gitlab.up.pt using docker:

```sh
docker login gitlab.up.pt:5050
```

After that, the project is available by running the following command to download and run the container:

```sh
docker run -d --name lbaw2411 -p 8001:80 gitlab.up.pt:5050/lbaw/lbaw2425/lbaw2411
```

This should make the website available at `localhost:8001`.

## Usage

### Administration Credentials

> Administration URL: http://localhost:8001/admin

| E-mail               | Password   |
| -------------------- | ---------- |
| kherrera@program.com | AdminsRule |

### User Credentials

| Type | Username              | Password     |
| ---- | --------------------- | ------------ |
| User | cindyburton@gmail.com | ILoveProgram |

### Password Recovery (Mailtrap)

> Mailtrap URL: https://mailtrap.io/inboxes/3327402/messages

**Login with the Google account:**

| E-mail                | Password        |
| --------------------- | --------------- |
| programlbaw@gmail.com | *************** |

### Real-Time Notifications (Pusher)

> Pusher URL: https://dashboard.pusher.com/apps/1909573

**Login with the Google account:**

| E-mail                | Password        |
| --------------------- | --------------- |
| programlbaw@gmail.com | *************** |

### External API (Postman)

We developed an external API which users can access with a generated token.  
The API reference is located here: http://127.0.0.1:8001/apireference.  
Tokens can be generated from the user settings or by filling this link: http://127.0.0.1:8001/user/{user-id}/token.  

To use the API, we provide the following Postman collection: [ProGram API](https://www.postman.com/programlbaw/workspace/program-api/collection/40575933-cbac648a-3f42-4ed8-ba1f-5ce20919677d?action=share&creator=40575933)

Alternatively, you can use the following Curl command structure to access the API:

```sh
curl -X GET "http://localhost:8001/api/<ROUTE>" \
    -H "Authorization: Bearer <ACCESS_TOKEN>"
```

## Learn More

In order to learn more about our project, we encourage you to check the following documents:
* [Main wiki page](https://github.com/racoelhosilva/LBAW_proGram/wiki/home)
* [ER: Requirements Specification](https://github.com/racoelhosilva/LBAW_proGram/wiki/er)
* [EBD: Database Specification](https://github.com/racoelhosilva/LBAW_proGram/wiki/ebd)
* [EAP: Architecture Specification and Prototype](https://github.com/racoelhosilva/LBAW_proGram/wiki/eap)
* [PA: Product and Presentation](https://github.com/racoelhosilva/LBAW_proGram/wiki/pa)

## Team

* Bruno Ricardo Soares Pereira de Sousa Oliveira, up202208700@up.pt
* Henrique Sardo Fernandes, up202204988@up.pt
* José Carlos Malheiro de Sousa, up202208817@up.pt
* Rodrigo Albergaria Coelho e Silva, up202205188@up.pt

***
GROUP2411, December 2024

--- 

> Group: 11  
> Final Grade: 19.2  
> Professors: Sérgio Nunes   
> Created in March 2024 for LBAW (Laboratório de Bases de Dados e Aplicações Web) [FEUP-L.EIC023]  
