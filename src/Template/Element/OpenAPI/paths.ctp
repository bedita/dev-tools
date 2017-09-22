paths:
  /status:
      summary: API Status
      description: Service status response
      security:
        - apikey: []
      responses:
        '200':
          description:
          content:
            application/json:
              schema:
                $ref: "#/components/schemas/Status"
      default:
        description: Unexpected error
          content:
            application/json:
              schema:
                $ref: "#/components/schemas/Error"
