#!/bin/bash

# Quick Git Push Script
# Automates the git add, commit, and push process

echo "========================================="
echo "Git Quick Push"
echo "========================================="
echo ""

# Check if we're in a git repository
if [ ! -d .git ]; then
    echo "✗ Error: Not a git repository"
    exit 1
fi

# Check for uncommitted changes
if [ -z "$(git status --porcelain)" ]; then
    echo "→ No changes to commit"
    echo ""
else
    # Show status
    echo "→ Files to be committed:"
    git status --short
    echo ""
    
    # Add all changes
    echo "→ Adding all changes..."
    git add -A
    
    # Prompt for commit message or use default
    read -p "Enter commit message (or press Enter for default): " COMMIT_MSG
    if [ -z "$COMMIT_MSG" ]; then
        COMMIT_MSG="Update deployment scripts and configurations"
    fi
    
    # Commit
    echo "→ Committing changes..."
    git commit -m "$COMMIT_MSG"
    echo ""
fi

# Get current branch
BRANCH=$(git branch --show-current)

# Push to remote
echo "→ Pushing to origin/$BRANCH..."
if git push origin $BRANCH; then
    echo ""
    echo "========================================="
    echo "✓ Successfully pushed to GitHub!"
    echo "========================================="
    echo ""
    echo "Branch: $BRANCH"
    echo "Remote: origin"
    echo ""
else
    echo ""
    echo "========================================="
    echo "✗ Push failed!"
    echo "========================================="
    echo ""
    echo "Please check your internet connection and GitHub access."
    exit 1
fi
