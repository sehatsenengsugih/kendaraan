describe('Sidebar Mobile Test', () => {
  beforeEach(() => {
    // Set mobile viewport
    cy.viewport(390, 844)
  })

  it('Test sidebar toggle', () => {
    cy.visit('/login')
    cy.get('input[name="email"]').type('admin@kas.or.id')
    cy.get('input[name="password"]').type('password')
    cy.get('button[type="submit"]').click()
    cy.url().should('include', '/dashboard')

    // Initial state - sidebar should be hidden (off-screen)
    cy.get('.sidebar-wrapper').should('have.css', 'left', '-308px')

    // Click hamburger menu to open sidebar
    cy.get('.header-mobile .drawer-btn').first().click({ force: true })
    cy.wait(600)

    // Sidebar should now be visible
    cy.get('.sidebar-wrapper').should('have.css', 'left', '0px')

    // Verify close button is visible
    cy.get('.sidebar-close-header .drawer-btn').should('be.visible')

    // Click close button in sidebar to hide
    cy.get('.sidebar-close-header .drawer-btn').click({ force: true })
    cy.wait(600)

    // Sidebar should be hidden again
    cy.get('.sidebar-wrapper').should('have.css', 'left', '-308px')
  })
})
