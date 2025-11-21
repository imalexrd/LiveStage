from playwright.sync_api import sync_playwright, expect
import time

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # 1. Login
    print("Navigating to login...")
    page.goto("http://127.0.0.1:8000/login")

    print("Logging in...")
    # Using generic selectors based on standard Laravel Breeze
    page.fill('input[type="email"]', "manager@example.com")
    page.fill('input[type="password"]', "password")
    page.click('button:has-text("Log in")')

    # Wait for navigation to dashboard
    try:
        page.wait_for_url("**/dashboard", timeout=10000)
        print("Logged in.")
    except:
        print("Login timeout/failure? Taking screenshot.")
        page.screenshot(path="/home/jules/verification/login_fail.png")
        browser.close()
        return

    # 2. Go to Musician Profile
    print("Navigating to Musician Profile...")
    page.goto("http://127.0.0.1:8000/musician-profile")

    # 3. Verify Calendar is visible
    print("Checking for Availability Calendar...")
    expect(page.get_by_text("Availability Calendar")).to_be_visible()

    # Scroll to calendar
    calendar_header = page.get_by_text("Availability Calendar")
    calendar_header.scroll_into_view_if_needed()

    # Wait for FullCalendar to load
    # .fc-view-harness is a standard class
    expect(page.locator(".fc-view-harness")).to_be_visible()

    # Screenshot 1: Initial View
    page.screenshot(path="/home/jules/verification/calendar_initial.png")
    print("Screenshot 1 taken.")

    # 4. Click a date (e.g., 15th cell)
    # We target a dayframe
    day_cell = page.locator(".fc-daygrid-day").nth(15)
    day_cell.click()

    # 5. Check Modal
    expect(page.locator("h3").filter(has_text="Block Date")).to_be_visible()
    # Wait for animation if any
    time.sleep(0.5)
    page.screenshot(path="/home/jules/verification/calendar_modal.png")
    print("Screenshot 2 taken (Modal).")

    # 6. Block it
    page.fill('input[placeholder="e.g. Vacation"]', "Test Block")
    page.get_by_role("button", name="Block Date").click()

    # 7. Verify Block appears
    # Wait for the event to appear
    expect(page.locator(".fc-event-title", has_text="Test Block")).to_be_visible()

    # Scroll again just in case
    calendar_header.scroll_into_view_if_needed()
    time.sleep(0.5)

    page.screenshot(path="/home/jules/verification/calendar_blocked.png")
    print("Screenshot 3 taken (Blocked).")

    browser.close()

if __name__ == "__main__":
    with sync_playwright() as playwright:
        run(playwright)
