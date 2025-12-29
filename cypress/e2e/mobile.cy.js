describe('Mobile View Test', () => {
  beforeEach(() => {
    cy.visit('/login')
    cy.get('input[name="email"]').type('admin@kas.or.id')
    cy.get('input[name="password"]').type('password')
    cy.get('button[type="submit"]').click()
    cy.url().should('include', '/dashboard')
  })

  it('Dashboard mobile view', () => {
    cy.wait(1000)
    cy.screenshot('mobile-dashboard')
  })

  it('Kendaraan table mobile view', () => {
    cy.visit('/kendaraan')
    cy.wait(1000)
    cy.screenshot('mobile-kendaraan')
  })

  it('Pajak table mobile view', () => {
    cy.visit('/pajak')
    cy.wait(1000)
    cy.screenshot('mobile-pajak')
  })

  it('Paroki table mobile view', () => {
    cy.visit('/paroki')
    cy.wait(1000)
    cy.screenshot('mobile-paroki')
  })
})
