# WP JSON Filter
Plugin WordPress que acrescenta novas endpoints para a WP REST API. A intenção do plugin é simplificar a interface JSON das rotas padrões do WordPress, como leitura de posts, por exemplo.

## Motivação
O WordPress por ter um viés multi proposta fornece uma interface API REST com informações que podem ser consideradas excessivas para projetos que utilizem poucas funcionalidades da plataforma. O cenário que inspira o plugin é a utilização do WordPress como backend fornecendo uma interface gráfica para gerenciamento do conteúdo através do `/wp-admin` e uma interface API REST que é consumida por uma aplicação mobile.  
A primeira intenção do plugin é criar uma interface API REST apenas com as informações necessárias para a apicação mobile. Assim sendo, diminuindo o tamanho da informação que trafega pela rede diminui também o tempo de espera do usuário, item necessário para trabalho de SEO. As interfaces MVP são todas de caráter público e não requerem autenticação.

## MVP
Requisições GET para leitura dos dados.  
Cada rota fornece dados necessários para uma tela do app mobile.
Requisição POST para escrita na rota de comentários do post.

### Interfaces REST
* GET /blog
  ```
  {
    status: 200|404|500,
    pageSize: 1|2|...,
    page: 1|2|...,
    data: [
      {
        id: int,
        date: string,
        content: string,
        title: string,
        name: string,
        excerpt: string,
        img: string,
        hashtags: [
          {
            id: int,
            name: string
          }
        ]
      },
      ...
    ]
  }
  ```
  
* GET /blog/{id}
  ```
  {
    status: 200|404|500,
    data: {
      id: int,
      date: string,
      content: string,
      title: string,
      name: string,
      excerpt: string,
      img: string,
      hashtags: [
        {
          id: int,
          name: string
        }
      ]
    }
  }
  ```
  
* GET /consultation
  ```
  {
    status: 200|404|500,
    data: [
      {
        id: int,
        title: string,
        name: string,
        img: string,
        hashtags: [
          {
            id: int,
            name: string
          }
        ]
      },
      ...
    ]
  }
  ```


* GET /shop
  ```
  {
    status: 200|404|500,
    pageSize: 1|2|...,
    page: 1|2|...,
    data: [
      {
        id: int,
        date: string,
        content: string,
        title: string,
        name: string,
        excerpt: string,
        img: string,
        hashtags: [
          {
            id: int,
            name: string
          }
        ]
      },
      ...
    ]
  }
  ```

* GET /shop/{id}
  ```
  {
    status: 200|404|500,
    data: {
      id: int,
      date: string,
      content: string,
      title: string,
      name: string,
      excerpt: string,
      img: string,
      hashtags: [
        {
          id: int,
          name: string
        }
      ]
    }
  }
  ```
  
* GET /blog/{id}/reviews
  ```
  {
    status: 200|404|500,
    pageSize: 1|2|...,
    page: 1|2|...,
    data: [
      {
        id: int,
        date: string,
        content: string,
        title: string,
        name: string,
        excerpt: string,
        img: string,
        hashtags: [
          {
            id: int,
            name: string
          }
        ]
      },
      ...
    ]
  }
  ```
 
 * POST /blog/{id}/reviews
  ```
  {
    status: 200|404|500,
    data: {
      id: int,
      date: string,
      name: string
    }
  }
  ```

* GET /shop/{id}/reviews/{rId}
  ```
  {
    status: 200|404|500,
    data: {
      id: int,
      date: string,
      content: string,
      title: string,
      name: string
    }
  }
  ```

### MVLP
Tela de customização no painel administrativo do WordPress.
Capacidade de informar os dados necessários de posts (apenas titulo e descrição, por exemplo).
