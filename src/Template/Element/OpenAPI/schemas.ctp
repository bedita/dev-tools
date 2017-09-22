  schemas:

    Links:
      properties:
        self:
          type: string
        home:
          type: string

    Status:
      properties:
        links:
          $ref: '#/components/schemas/Links'
        meta:
          properties:
            status:
              properties:
                environment:
                  type: string

    Error:
      properties:
        error:
          properties:
            status:
              type: string
            title:
              type: string
            code:
              type: string
            description:
              type: string
          links:
            $ref: '#/components/schemas/Links'
